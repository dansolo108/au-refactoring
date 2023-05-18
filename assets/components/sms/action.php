<?php
define('MODX_API_MODE', true);
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';
}
/** @var $modx gitModx */
$modx->getRequest();
$post = $modx->request->parameters['POST'];
$response = ['success' => false, 'message' => ''];
/** @var sms $sms */
$sms = $modx->getService('sms', 'sms', MODX_CORE_PATH . 'components/sms/model/sms/');
$sms->initialize();
$sms->mode = 'user';
$sms->values = $post;
$mode = $post['mode'] ? $post['mode'] : 'code';
$tpl = $post['tpl'] ? $post['tpl'] : '';
switch ($post['type']) {
    case "sendCode":
        $response = $sms->sendCode($tpl, $post['phone'], $mode);
        break;
    case "checkCode":
        $response = $sms->checkCode($post['phone'], $post['code'], $mode);
        if ($response['success']) {
            $sms->activateCode($post['phone'], $post['code'], $mode);
        }
        break;
}
exit(is_array($response) ? json_encode($response) : $response);