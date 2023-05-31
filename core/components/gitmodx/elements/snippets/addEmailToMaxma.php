<?php

if (empty(trim($_POST['phone']))) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Вы не заполнили телефон');
    return false;
}

if (empty(trim($_POST['email']))) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Вы не заполнили почту');
    return false;
}

$maxmaPath = MODX_CORE_PATH . 'components/stik/model/';
$modx->loadClass('maxma', $maxmaPath, true, true);
$maxma = new maxma($modx);

if (!($maxma instanceof maxma)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Ошибка подключения modMaxma!');
    return false;
}

$phoneNumber = preg_replace('/[^0-9+]/', '', trim($_POST['phone']));
$data['email'] = $email;
$data['isEmailSubscribed'] = true;

if (is_numeric($phoneNumber) && strlen($phoneNumber) == 11 && $phoneNumber[0] == '7') {
    $phoneNumber = '+'.$phoneNumber;
}

if (strlen($phoneNumber) !== 12) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'В номере должно быть 12 символов '.$phoneNumber);
    return false;
} else {
    $maxma->updateClientByPhone($phoneNumber, $data);
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Адрес почты клиента отправлен в Maxma');
    return true;
}