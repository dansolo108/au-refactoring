<?php

if (empty(trim($_POST['tel']))) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Вы не заполнили телефон');
    return false;
}

if (empty(trim($_POST['prize']))) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Приз не выбран');
    return false;
}

if (!empty($errors)) {
    // return $AjaxForm->error('В форме содержатся ошибки!', $errors);
    $modx->log(xPDO::LOG_LEVEL_ERROR, $errors['tel']);
    return false;
} else {

    $maxmaPath = MODX_CORE_PATH . 'components/stik/model/';
    $modx->loadClass('maxma', $maxmaPath, true, true);
    $maxma = new maxma($modx);

    if (!($maxma instanceof maxma)) {

      $modx->log(xPDO::LOG_LEVEL_ERROR, 'Ошибка подключения maxma!');
      return false;

    }

    $tel = trim($_POST['tel']);
    $data['phoneNumber'] = preg_replace('/[^0-9]/', '', $tel);

    $prize = trim($_POST['prize']);
    switch ($prize) {
        case 'pdrk':
            $promocode = 'GIFT';
            break;
        case 'frdm':
            $promocode = 'FREEDOM14';
            break;
        case 'astr':
            $promocode = 'ASTER';
            break;
        case 'mprl':
            $promocode = 'MYAPRIL23';
            break;
        case 'vlt':
            $promocode = 'VIOLET';
            break;
        case 'tlp':
            $promocode = 'TULIP';
            break;
        case 'scs':
            $promocode = 'SUCCESS';
            break;
    }

    if (strlen($data['phoneNumber']) !== 11) {

        $modx->log(xPDO::LOG_LEVEL_ERROR, 'В номере должно быть 11 цифр');
        return false;
        
    } else {

        $maxma->createNewClient($data);
        $modx->log(xPDO::LOG_LEVEL_ERROR, 'Телефон отправлен в maxma');
            
        $smsPath = MODX_CORE_PATH . 'components/sms/model/sms/';
        $modx->loadClass('sms', $smsPath, true, true);
        $sms = new sms($modx);
        if (!($sms instanceof sms)) {
            // return $AjaxForm->error('Ошибка подключения maxma!');
          $modx->log(xPDO::LOG_LEVEL_ERROR, 'Ошибка подключения sms!');
          return false;
        }
        $sms->initialize();
        $sms->mode = "user";
        $sms->sendSms('Ваш промокод: '.$promocode, $data['phoneNumber']);
        return true;
 
    }

}