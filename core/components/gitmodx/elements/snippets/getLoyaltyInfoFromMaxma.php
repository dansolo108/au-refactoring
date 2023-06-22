<?php

$maxmaPath = MODX_CORE_PATH . 'components/stik/model/';
$modx->loadClass('maxma', $maxmaPath, true, true);
$maxma = new maxma($modx);

if (!($maxma instanceof maxma)) {
  $modx->log(1, 'Ошибка подключения maxma!');
  return false;
}

$profile = $modx->user->getOne('Profile');
$phone = $profile ? $profile->get('mobilephone') : '';

if (empty($phone)) {
    $modx->log(1, 'В профиле не указан номер телефона!');
    return false;
}

$client = $maxma->getClientInfo('+'.$phone);

// $modx->log(1, print_r($client, 1));

$data = $client['level'];
$data['bonus'] = $client['bonuses'][0]['amount'];

if ($data) {
    $modx->setPlaceholders($data, 'loyalty.');
}

return;