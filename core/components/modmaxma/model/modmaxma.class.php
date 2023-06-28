<?php
include_once MODX_CORE_PATH . '/model/modx/rest/modrest.class.php';
class modMaxma
{
    /** @var modX $modx */
    public $modx;
    /** @var pdoFetch $pdoTools */
    public $pdoTools;
    public string $version = '1.0.1';

    public array $config = [];
    public string $settings_prefix = 'modmaxma_';

    public modRest $modRest;

    public miniShop2 $ms2;

    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = &$modx;
        /** @var modNamespace $namespace */
        $namespace = $modx->getObject('modNamespace', 'modMaxma');
        $corePath = $namespace->getCorePath();
        $assetsPath = $namespace->getAssetsPath();
        $assetsUrl = str_replace(MODX_BASE_PATH, '', $assetsPath);
        if ($assetsUrl[0] !== '/') {
            $assetsUrl = '/' . $assetsUrl;
        }
        //дефолтные значения заменяем значениями из настроек
        $this->config = $this->getOptions([
            'core_path' => $corePath,
            'model_path' => $corePath . 'model/',
            'processors_path' => $corePath . 'processors/',
            'polling_rate' => 5,
            //front
            'frontend_js' => $assetsUrl . 'js/web/default.js',
            // 'frontend_css' =>$assetsUrl . 'css/default.css',
            'action_url' => $assetsUrl . 'action.php',
            'assets_url' => $assetsUrl,
            'css_url' => $assetsUrl . 'css/',
            'js_url' => $assetsUrl . 'js/',
            //api
            'apiKey' => $this->modx->getOption('stik_maxma_api_key'),
            'serverAddress' => $this->modx->getOption('stik_maxma_url'), // api-test.cloudloyalty.ru
            'shopCode' => $this->modx->getOption('stik_maxma_shop_code'),
            'shopName' => $this->modx->getOption('stik_maxma_shop_name'),
        ]);
        // переопределяем на те которые зашли в конструктор
        $this->config = array_merge($this->config, $config);

        $this->modx->addPackage('modmaxma', $this->config['model_path']);
        $this->modx->lexicon->load('modmaxma:default');
        if ($this->pdoTools = $this->modx->getService('pdoFetch'))
            $this->pdoTools->setConfig($this->config);
        $this->modRest = new modRest($this->modx);
        $this->modRest->setOption('baseUrl', rtrim($this->config['serverAddress'], '/'));
        $this->modRest->setOption('format', 'json');
        $this->modRest->setOption('suppressSuffix', true);
        $this->modRest->setOption('headers', [
            'Accept' => 'application/json',
            'Content-type' => 'application/json', // Сообщаем сервису, что хотим получить ответ в json формате
            'X-Processing-Key' => $this->config['apiKey']
        ]);
        $this->ms2 = &$modx->getService('minishop2');
        if (empty($this->ms2)) {
            $this->modx->log(1, 'could not load minishop');
            throw new ErrorException('could not load minishop');
        }
        $this->ms2->initialize();
        $this->loadFrontend();
    }
    function getOption($key, $options = null, $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption($this->settings_prefix . $key, $options, $default, $skipEmpty);
    }

    function getOptions($defaultOptions, $prefix = '')
    {
        foreach ($defaultOptions as $key => &$option) {
            $option = $this->getOption($prefix . $key, null, $option);
        }
        return $defaultOptions;
    }

    function loadFrontend()
    {
        $config = $this->pdoTools->makePlaceholders($this->config);
        // Register JS
        $js = trim($this->config['frontend_js']);
        if (!empty($js) && preg_match('/\.js/i', $js)) {
            if (preg_match('/\.js$/i', $js)) {
                $js .= '?v=' . substr(md5($this->version), 0, 10);
            }
            $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
            // готовим js конфиг на фронт
            $js_settings = [
                'action_url' => $this->config['action_url'],
            ];
            $data = json_encode($js_settings);
            $this->modx->regClientHTMLBlock(
                "<script>
                    document.addEventListener('miniShop2Initialize',e=>{
                        window.miniShop2.modMaxma = new modMaxma({$data});
                    })
                </script>"
            );
        }
        // $css = trim($this->config['frontend_css']);
        // if (!empty($js) && preg_match('/\.css/i', $css)) {
        //     if (preg_match('/\.css$/i', $css)) {
        //         $css .= '?v=' . substr(md5($this->version), 0, 10);
        //     }
        //     $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
        // }
    }

    // Создание нового клиента
    public function createNewClient(array $data)
    {
        if (!empty($data['phoneNumber'])) {
            $client = $this->getClientInfo($data['phoneNumber']);
        }
        if (!$client) {
            $params = [
                'client' => $data
                // [
                //     'phoneNumber' => '+79514877500',
                //     'email' => 'ig0r74@yandex.com',
                //     'surname' => 'Терентьев',
                //     'name' => 'Игорь',
                //     'externalId' => '5',
                // ]
            ];

            $response = $this->modRestClient->post('new-client', $params);
            $client = $response->process();
        }

        if (isset($client['errorCode'])) {
            $this->modx->log(1, 'Maxma createNewClient error: ' . print_r($client, 1));
            return false;
        } else {
            return $client;
        }
    }

    // Получает общую информацию о клиенте
    public function getClientInfo(string $input, string $type = 'phoneNumber')
    {
        if (!in_array($type, ['phoneNumber', 'externalId', 'card']) || !$input) return false;
        if ($type == 'phoneNumber') $input = $this->phoneFormatting($input);

        $params = [];
        $params[$type] = $input;

        $response = $this->modRestClient->post('get-balance', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            // $this->modx->log(1, 'Maxma getClientInfo error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Получает баланс бонусов по номеру телефона
    public function getClientBalanceByPhone($phoneNumber)
    {
        $data = $this->getClientInfo($phoneNumber);
        return $data['client']['bonuses'] ?: 0;
    }

    // Получает баланс бонусов по id пользователя в MODX
    public function getClientBalanceByExternalId($externalId)
    {
        $data = $this->getClientInfo($externalId, 'externalId');
        return $data['client']['bonuses'] ?: 0;
    }

    // Преобразование телефона к единому виду
    public function preparePhone($phone)
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        return $phone;
    }

    public function setUserphone($phone)
    {
        $this->userphone = $this->phoneFormatting($phone);
    }

    // Обновляет информацию о клиенте.
    // Доступные параметры https://docs.maxma.com/api/#tag/Rabota-s-klientskoj-bazoj/paths/~1update-client/post
    public function updateClient(array $data)
    {
        $params['client'] = $data;
        $params['phoneNumber'] = $this->userphone;

        $response = $this->modRestClient->post('update-client', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma updateClient error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Обновляет баланс клиента
    public function adjustClientBalance(int $amount, $phone = '')
    {
        if (!$phone) $phone = $this->userphone;
        if (!$phone) return false;

        $params = [];
        $params['client']['phoneNumber'] = $phone;
        $params['balanceAdjustment']['amountDelta'] = $amount;

        $response = $this->modRestClient->post('adjust-balance', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma adjustClientBalance error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Проверка хватает-ли баланса
    public function checkBonuses($bonuses)
    {
        if (!$this->userphone) return $this->modx->lexicon('stik_loyalty_err_not_confirmed');

        $balance = $this->getClientBalanceByPhone($this->userphone);
        $balance = str_replace(' ', '', $this->modx->runSnippet('msMultiCurrencyPriceFloor', ['price' => $balance, 'format' => false]));

        if ($bonuses <= $balance) return true;

        return $this->modx->lexicon('stik_loyalty_err_not_enough_bonuses');
    }


    public function setPromocode($promocode)
    {
        $this->calculatePurchase($promocode);
    }

    public function canUsePromocode($promocode)
    {
        $result = $this->calculatePurchase($promocode);
        return $result['calculationResult']['promocode']['applied'] ? true : false;
    }

    // Создание/изменение заказа
    public function setOrder(string $order_id)
    {
        $order = $this->modx->getObject('msOrder', $order_id);
        $products = $order->getMany('Products');
        $phone = $this->phoneFormatting($order->UserProfile->get('mobilephone'));
        $queryArr = [
            'orderId' => $order_id,
            'calculationQuery' => [
                'client' => [
                    'phoneNumber' => (string)$phone
                ],
                'shop' => [
                    'code' => $this->config['shopCode'],
                    'name' => $this->config['shopName'],
                ],
                'rows' => [],
            ],
        ];
        $counter = 0;
        foreach ($products as $orderProduct) {
            $product = $orderProduct->getOne('Product');
            $productArr = [
                'id' => (string)$product->get('id'),
                'product' => [
                    'sku' => $product->get('article'),
                    'title' => $product->get('pagetitle'),
                    'blackPrice' => $orderProduct->get('price'),
                ],
                'qty' => $orderProduct->get('count'),
            ];
            $queryArr['calculationQuery']['rows'][$counter] = $productArr;
            $counter++;
        }
        $response = $this->modRest->post('v2/set-order', $queryArr);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma setOrder error: ' . var_export($data, 1));
            return false;
        } else {
            return $data;
        }
    }
    // Подтверждение оплаты заказа
    public function confirmOrder(string $order_id)
    {
        $response = $this->modRest->post('confirm-order', ['orderId' => $order_id]);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma confirmOrder error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Отмена заказа и возврат зарезервированных бонусов
    public function cancelOrder(string $order_id)
    {
        $response = $this->modRest->post('cancel-order', ['orderId' => $order_id]);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma cancelOrder error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    /**
     * Высчитвает заказ, без его создания. Используется для промокодов и бонусов
     * @param int $bonuses
     * @param string $promocode
     * @return array|false
     */
    public function calculatePurchase($cart, $user_id = null, $bonuses = 0, string $promocode = '')
    {
        if (count($cart) == 0) {
            return false;
        }
        $params = [
            'calculationQuery' => [
                'shop' => [
                    'code' => $this->config['shopCode'],
                    'name' => $this->config['shopName'],
                ],
                'applyBonuses' => $bonuses,
                'rows' => [],
            ]
        ];
        if ($user_id) {
            $params['calculationQuery']['client']['externalId'] = (string)$user_id;
        }
        if ($promocode) {
            $params['calculationQuery']['promocode'] = $promocode;
        }
        foreach ($cart as $entry) {
            /** @var Modification $modification */
            $modification = $this->modx->getObject('Modification', $entry['id']);
            /** @var msProduct $product */
            $product = $modification->getOne('Product');
            if (empty($entry['price']) || empty($entry['count']))
                continue;
            $params['calculationQuery']['rows'][] = [
                'product' => [
                    'sku' => (string)$modification->get('code') ?: $product->get('article'),
                    'title' => $product->get('pagetitle'),
                    'blackPrice' => $modification->get('price'),
                ],
                'qty' => (int)$entry['count'],
            ];
        }
        if (count($params['calculationQuery']['rows']) == 0) {
            $this->modx->log(1, 'Maxma calculatePurchase error (count or price)');
            return false;
        }
        $response = $this->modRest->post('v2/calculate-purchase', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma calculatePurchase error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }
    
    /**
     * Высчитвает скидку по промокоду, без создания заказа.
     * @param string $promocode
     * @return array|false
     */
    public function calculatePurchaseOneProduct(array $product, string $promocode)
    {
        $params = [
            'calculationQuery' => [
                'shop' => [
                    'code' => $this->config['shopCode'],
                    'name' => $this->config['shopName'],
                ],
                'promocode' => $promocode,
                'rows' => [
                    [
                        'product' => [
                            'sku' => $product['sku'],
                            'blackPrice' => (float) $product['price'],
                        ],
                        'qty' => 1,
                    ],
                ],
            ]
        ];
        $response = $this->modRest->post('v2/calculate-purchase', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma calculatePurchaseOneProduct error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }
    
    public function calculateCurrentOrder($bonuses = null, $promocode = null)
    {
        $order = $this->ms2->order->get();
        $user_id = null;
        if ($user =  $this->modx->getAuthenticatedUser('web')) {
            $user_id = $user->get('id');
        }
        if ($promocode == null) {
            $promocode = $order['promocode'] ?: '';
        }
        if ($bonuses  == null) {
            $bonuses = $order['bonuses'] ?: 0;
        }
        return $this->calculatePurchase($this->ms2->cart->config['cart'], $user_id, $bonuses, $promocode);
    }


    public function createGiftCard(string $code)
    {
        $params = [
            'code' => $code,
        ];
        $response = $this->modRest->post('generate-gift-card', $params);
        $data = $response->process();
        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma createGiftCard error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Преобразование телефона к единому виду 2.0
    public function phoneFormatting(string $phone)
    {
        $trim = trim($phone);
        $formatted = preg_replace(
            array(
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ),
            array(
                '+7$2$3$4$5',
                '+7$2$3$4$5',
                '+7$2$3$4$5',
                '+7$2$3$4$5',
                '+7$2$3$4',
                '+7$2$3$4',
            ),
            $trim
        );
        return $formatted;
    }

    public function calculateOrder($bonuses = null, $promocode = null)
    {
        $order = $this->ms2->order->get();
        $phone = null;

        if ($user =  $this->modx->getAuthenticatedUser('web')) {

            $profile = $user->getOne('Profile');
            $userPhone = $this->phoneFormatting($profile->get('mobilephone'));
            if ($this->getClientInfo($userPhone)) {
                $phone = $userPhone;
            }

        }

        if ($promocode == null) {
            $promocode = $order['promocode'] ?: '';
        }

        if ($bonuses  == null) {
            $bonuses = $order['bonuses'] ?: 0;
        }

        return $this->calculatePurchaseV2($this->ms2->cart->config['cart'], $phone, $bonuses, $promocode);
    }

    public function calculatePurchaseV2($cart, $phone = null, $bonuses = 0, string $promocode = '')
    {
        if (count($cart) == 0) {
            return false;
        }
        $params = [
            'calculationQuery' => [
                'shop' => [
                    'code' => $this->config['shopCode'],
                    'name' => $this->config['shopName'],
                ],
                'applyBonuses' => $bonuses,
                'rows' => [],
            ]
        ];
        if ($phone) {
            $params['calculationQuery']['client']['phoneNumber'] = (string)$phone;
        }
        if ($promocode) {
            $params['calculationQuery']['promocode'] = $promocode;
        }
        foreach ($cart as $entry) {
            /** @var Modification $modification */
            $modification = $this->modx->getObject('Modification', $entry['id']);
            /** @var msProduct $product */
            $product = $modification->getOne('Product');
            if (empty($entry['price']) || empty($entry['count']))
                continue;
            $params['calculationQuery']['rows'][] = [
                'product' => [
                    'sku' => (string)$modification->get('code') ?: $product->get('article'),
                    'title' => $product->get('pagetitle'),
                    'blackPrice' => $modification->get('price'),
                ],
                'qty' => (int)$entry['count'],
            ];
        }
        if (count($params['calculationQuery']['rows']) == 0) {
            $this->modx->log(1, 'Maxma calculatePurchaseV2 error (count or price)');
            return false;
        }
        $response = $this->modRest->post('v2/calculate-purchase', $params);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma calculatePurchaseV2 error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }
}
