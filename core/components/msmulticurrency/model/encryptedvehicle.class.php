<?php

class encryptedVehicle extends xPDOObjectVehicle
{
    public $class = 'encryptedVehicle';
    const version = '2.0.0';
    const cipher = 'AES-256-CBC';


    /**
     * @param $transport xPDOTransport
     * @param $object
     * @param array $attributes
     */
    public function put(&$transport, &$object, $attributes = array())
    {
        parent::put($transport, $object, $attributes);

        if (defined('PKG_ENCODE_KEY')) {
            $this->payload['object_encrypted'] = $this->encode($this->payload['object'], PKG_ENCODE_KEY);
            unset($this->payload['object']);

            if (isset($this->payload['related_objects'])) {
                $this->payload['related_objects_encrypted'] = $this->encode($this->payload['related_objects'], PKG_ENCODE_KEY);
                unset($this->payload['related_objects']);
            }
            $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Package encrypted!');
            //$transport->xpdo->log(modX::LOG_LEVEL_ERROR, 'Package encrypted! '.print_r($attributes, 1));
        }
    }


    /**
     * @param $transport xPDOTransport
     * @param $options
     *
     * @return bool
     */
    public function install(&$transport, $options)
    {
        if (!$this->decodePayloads($transport, 'install')) {
            $this->errorBuyMessage($transport);
            exit;
            return false;
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Package decrypted!');
        }

        return parent::install($transport, $options);
    }

    /**
     * @param xPDOTransport $transport
     */
    private function errorBuyMessage(&$transport)
    {
        $message = 'Installation Error! Package not purchased for this site.';
        if ($transport->xpdo->getOption('manager_language') == 'ru') {
            $message = 'Ошибка установки! Пакет не куплен для этого сайта.';
        }
        $transport->xpdo->log(modX::LOG_LEVEL_ERROR, $message);
    }


    /**
     * @param $transport xPDOTransport
     * @param $options
     *
     * @return bool
     */
    public function uninstall(&$transport, $options)
    {
        if (!$this->decodePayloads($transport, 'uninstall')) {
            return false;
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Package decrypted!');
        }

        return parent::uninstall($transport, $options);
    }


    /**
     * @param array $data
     * @param string $key
     *
     * @return string
     */
    protected function encode($data, $key)
    {
        $ivLen = openssl_cipher_iv_length($this::cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);
        $cipher_raw = openssl_encrypt(serialize($data), $this::cipher, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $cipher_raw);
    }


    /**
     * @param string $string
     * @param string $key
     *
     * @return string
     */
    protected function decode($string, $key)
    {
        $ivLen = openssl_cipher_iv_length($this::cipher);
        $encoded = base64_decode($string);
        if (ini_get('mbstring.func_overload')) {
            $strLen = mb_strlen($encoded, '8bit');
            $iv = mb_substr($encoded, 0, $ivLen, '8bit');
            $cipher_raw = mb_substr($encoded, $ivLen, $strLen, '8bit');
        } else {
            $iv = substr($encoded, 0, $ivLen);
            $cipher_raw = substr($encoded, $ivLen);
        }
        return unserialize(openssl_decrypt($cipher_raw, $this::cipher, $key, OPENSSL_RAW_DATA, $iv));
    }


    /**
     * @param $transport xPDOTransport
     * @param string $action
     *
     * @return bool
     */
    protected function decodePayloads(&$transport, $action = 'install')
    {
        if (isset($this->payload['object_encrypted']) || isset($this->payload['related_objects_encrypted'])) {
            if (!$key = $this->getDecodeKey($transport, $action)) {
                return false;
            }
            if (isset($this->payload['object_encrypted'])) {
                $this->payload['object'] = $this->decode($this->payload['object_encrypted'], $key);
                unset($this->payload['object_encrypted']);
            }
            if (isset($this->payload['related_objects_encrypted'])) {
                $this->payload['related_objects'] = $this->decode($this->payload['related_objects_encrypted'], $key);
                unset($this->payload['related_objects_encrypted']);
            }
        }

        return true;
    }


    /**
     * @param $transport xPDOTransport
     * @param $action
     *
     * @return bool|string
     */
    protected function getDecodeKey(&$transport, $action)
    {
        return "a2baa465cf027e0de259ca38bad2aaeaa47b2565";
    }

}