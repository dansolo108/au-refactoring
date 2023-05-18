<?php

class maxmaUser extends modUser
{
    public xPDO $modx;
    public array $maxmaConfig;
    protected modRest $modRest;
    public modUserProfile $lodadedProfile;
    protected $_originalFieldMeta;

    public function __construct(xPDO &$xpdo)
    {
        $this->modx = $xpdo;
        $this->maxmaConfig = [
            'apiKey' => $this->modx->getOption('stik_maxma_api_key'),
            'serverAddress' => $this->modx->getOption('stik_maxma_url'), // api-test.cloudloyalty.ru
            'shopCode' => $this->modx->getOption('stik_maxma_shop_code'),
            'shopName' => $this->modx->getOption('stik_maxma_shop_name'),
        ];
        $this->modx->getService('rest', 'rest.modRest');
        $this->modRest = new modRest($this->modx);
        $this->modRest->setOption('baseUrl', rtrim($this->maxmaConfig['serverAddress'], '/'));
        $this->modRest->setOption('format', 'json');
        $this->modRest->setOption('suppressSuffix', true);
        $this->modRest->setOption('headers', [
            'Accept' => 'application/json',
            'Content-type' => 'application/json', // Сообщаем сервису, что хотим получить ответ в json формате
            'X-Processing-Key' => $this->maxmaConfig['apiKey']
        ]);
        parent::__construct($xpdo);
        parent::set('class_key', 'maxmaUser');
        $this->_originalFieldMeta = $this->_fieldMeta;

    }

    /**
     * @return modUserProfile
     */
    public function loadProfile(): modUserProfile
    {
        if (empty($this->lodadedProfile) || !is_object($this->lodadedProfile) || !($this->lodadedProfile instanceof modUserProfile)) {
            if (!$this->lodadedProfile = $this->getOne('Profile')) {
                $this->lodadedProfile = $this->xpdo->newObject('modUserProfile');
                parent::addOne($this->lodadedProfile);
            }
        }
        return $this->lodadedProfile;
    }

    /**
     * @param array|string $k
     * @param null $format
     * @param null $formatTemplate
     *
     * @return mixed|null|xPDOObject
     */
    public function get($k, $format = null, $formatTemplate = null)
    {
        if (is_array($k)) {
            return parent::get($k, $format, $formatTemplate);
        }else{
            if(key_exists($k,$this->_originalFieldMeta)){
                return parent::get($k, $format, $formatTemplate);
            }
            elseif(key_exists($k,$this->loadProfile()->_fields)) {
                return $this->loadProfile()->get($k, $format, $formatTemplate);
            }
        }
        return null;
    }
    function save($cacheFlag = false)
    {
        if($this->isNew()){
            $this->createNewClient();
        }
        return parent::save($cacheFlag); // TODO: Change the autogenerated stub
    }

    // Получает общую информацию о клиенте
    public function getMaxmaData()
    {
        $response = $this->modRest->post('get-balance', ["externalId" => (string)$this->get("id")]);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            if($data['errorCode'] == 3){
                return $this->createNewClient();
            }else{
                $this->modx->log(1, 'Maxma getClientInfo error: ' . print_r($data, 1));
                return false;
            }
        } else {
            return $data;
        }
    }

    // Получает баланс бонусов по номеру телефона
    public function getMaxmaBalance()
    {
        $data = $this->getMaxmaData();
        return $data['client']['bonuses'] ?: 0;
    }

    // Обновляет информацию о клиенте.
    // Доступные параметры https://docs.maxma.com/api/#tag/Rabota-s-klientskoj-bazoj/paths/~1update-client/post
    public function updateMaxmaData()
    {
        $params = [
            "email" => $this->get("email"),
            "surname" => $this->get("surname"),
            "name" => $this->get("name"),
            "fullName" => $this->get("fullname"),
            "birthdate" => $this->get("birthdate"),
            'phoneNumber' => $this->get("mobilephone")?:$this->get("phone"),
        ];
        foreach ($params as $key=>$param){
            if (empty($param))
                unset($params[$key]);
        }
        $response = $this->modRest->post('update-client', [
            "externalId" => (string)$this->get("id"),
            "client" => $params
        ]);
        $data = $response->process();

        if (isset($data['errorCode'])) {
            $this->modx->log(1, 'Maxma updateClient error: ' . print_r($data, 1));
            return false;
        } else {
            return $data;
        }
    }

    // Создание нового клиента
    public function createNewClient() {
        $params = [
                'phoneNumber' => $this->get("mobilephone")?:$this->get("phone"),
                'email' => $this->get("email"),
                'surname' => $this->get("surname"),
                'name' => $this->get("name"),
                'externalId' => (string)$this->get("id"),
            ];
        foreach ($params as $key=>$param){
            if (empty($param))
                unset($params[$key]);
        }
        $response = $this->modRest->post('new-client', [
            "client"=>$params,
            "shop"=>[
                "code"=>$this->maxmaConfig["shopCode"],
                "name"=>$this->maxmaConfig["shopName"],
            ]
        ]);
        $client = $response->process();
        if (isset($client['errorCode'])) {
            $this->modx->log(1, 'Maxma createNewClient error: ' . print_r($client, 1));
            return false;
        } else {
            return $client;
        }
    }
    /**
     * @param xPDO $xpdo
     * @param string $className
     * @param null $criteria
     * @param bool $cacheFlag
     *
     * @return msProduct
     */
    public static function load(xPDO &$xpdo, $className, $criteria = null, $cacheFlag = true)
    {
        if (!is_object($criteria)) {
            $criteria = $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        /** @noinspection PhpParamsInspection */
        $xpdo->addDerivativeCriteria($className, $criteria);

        return parent::load($xpdo, $className, $criteria, $cacheFlag);
    }


    /**
     * @param xPDO $xpdo
     * @param string $className
     * @param null $criteria
     * @param bool $cacheFlag
     *
     * @return array
     */
    public static function loadCollection(xPDO &$xpdo, $className, $criteria = null, $cacheFlag = true)
    {
        if (!is_object($criteria)) {
            $criteria = $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        /** @noinspection PhpParamsInspection */
        $xpdo->addDerivativeCriteria($className, $criteria);

        return parent::loadCollection($xpdo, $className, $criteria, $cacheFlag);
    }
}