<?php

/** @var $modx gitModx */
switch ($modx->event->name) {
    case "SMSAfterCodeCheck":

        $val = &$modx->event->returnedValues;

        if ($response['success']) {

            $path = MODX_CORE_PATH . 'components/stik/model/';
            $modx->loadClass('stikSms', $path, true, true);
            $stikSms = new stikSms($modx);

            if (!($stikSms instanceof stikSms)) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, 'loginAndRegister plugin: ошибка подключения stikSms (SMSAfterCodeCheck event)');
                return false;
            }

            // проверяем существование пользователя
            $user = $stikSms->findUserByPhone($phone);

            if ($user == false) {
                
                $user = $stikSms->createUser($phone);
                
                if (is_object($user)) {
                    $data = [];
                    
                    $maxmaPath = MODX_CORE_PATH . 'components/modmaxma/model/';
                    $modx->loadClass('modMaxma', $maxmaPath, true, true);
                    $maxma = new modMaxma($modx);
                    
                    if (!($maxma instanceof modMaxma)) {
                        $modx->log(xPDO::LOG_LEVEL_ERROR, 'loginAndRegister plugin: ошибка подключения maxma!');
                        return false;
                    }
                    
                    $data['phoneNumber'] = $phone;
                    $maxma->createNewClient($data);
                }
                
            }

            // авторизуем
            $stikSms->authenticate($user, 'web');
        }
        break;
    case 'msOnCreateOrder':
        // Сохраняем в профайл пользователя поля из заказа
        /** @var stikSms $stikSms */
        $stikSms = $modx->getService('stikSms', 'stikSms', $modx->getOption('core_path') . 'components/stik/model/', []);
        /**@var msOrderCustom $order */
        /** @var msOrder $msOrder */
        $msAddress = $msOrder->getOne('Address');
        $user = $modx->getObject('modUser', $msOrder->get('user_id'));
        $profile = $user->getOne('Profile');
        $fullname = $msAddress->get('name') . ' ' . $msAddress->get('surname');
        if (empty($profile->get('name'))) {
            $profile->set('name', $msAddress->get('name'));
        }
        if (empty($profile->get('surname'))) {
            $profile->set('surname', $msAddress->get('surname'));
        }
        if (empty($profile->get('fullname'))) {
            $profile->set('fullname', $fullname);
        }
        if (empty($profile->get('mobilephone'))) {
            $profile->set('mobilephone', $stikSms->preparePhone($msAddress->get('phone')));
        }
        $profile->save();
        // Авторизуем пользователя
        $user->addSessionContext('web');
        break;
    case "msOnBeforeGetOrderCustomer":
        /** @var msOrderCustom $order */
        $data = $order->get();
        /** @var stikSms $stikSms */
        $stikSms = $modx->getService('stikSms', 'stikSms', $modx->getOption('core_path') . 'components/stik/model/', []);
        $data['phone'] = $stikSms->preparePhone($data['phone']);
        if (strlen($data['phone']) !== 11) {
            $modx->event->output("В номере должно быть 11 цифр.");
            return false;
        }
        if (empty($data['receiver'])) {
            $order->add('receiver', $data['name'] . " " . $data['surname']);
        }
        if (!empty($data['phone'])) {
            $c = $modx->newQuery('modUser');
            $c->leftJoin('modUserProfile', 'Profile');
            $filter['Profile.mobilephone'] = $data['phone'];
            $c->where($filter);
            if ($user = $modx->getObject('modUser', $c)) {
                $scriptProperties['customer'] = $user;
            }
        }
        break;
    case "OnUserSave":
        /** @var $mode 'new' || 'upd' */
        if ($mode === "upd") {
            /** @var modUserProfile $profile */
            /** @var $user modUser */
            $profile = $user->getOne('Profile');
            ['name' => $name, 'surname' => $surname] = $profile->toArray();
            if ($name && $surname) {
                $profile->set('fullname', "{$name} {$surname}");
                $profile->save();
            }
        }
        break;
}
