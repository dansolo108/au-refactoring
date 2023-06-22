<?php

$maxmaPath = MODX_CORE_PATH . 'components/stik/model/';
$modx->loadClass('maxma', $maxmaPath, true, true);
$maxma = new maxma($modx);

if (!($maxma instanceof maxma)) {
  $modx->log(xPDO::LOG_LEVEL_ERROR, 'Ошибка подключения maxma!');
  return false;
}

$profile = $modx->user->getOne('Profile');
$phone = $profile ? $profile->get('mobilephone') : '';

if (empty($phone)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'В профиле не указан номер телефона!');
    return false;
}

$client = $maxma->getClientInfo('+'.$phone);

$data = $client['level'];
$data['bonus'] = $client['bonuses'];

if ($data) {
    $modx->setPlaceholders($data, 'loyalty.');
}

return;