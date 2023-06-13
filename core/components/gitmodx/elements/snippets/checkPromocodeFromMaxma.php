<?php

$values = $hook->getValues();

if (empty($values['promocode'])) {
    return $AjaxForm->error('Ошибки в форме', array(
        'promocode' => 'Вы не ввели промокод'
    ));
}

$maxmaPath = MODX_CORE_PATH . 'components/modmaxma/model/';
$modx->loadClass('modMaxma', $maxmaPath, true, true);
$maxma = new modMaxma($modx);

if (!($maxma instanceof modMaxma)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Ошибка подключения maxma!');
    return false;
}

$tmp = explode(',', $values['product']);

if (count($tmp) == 2 && is_numeric($tmp[1])) {

    $product = [
        'sku' => $tmp[0],
        'price' => $tmp[1],
    ];
    $result = $maxma->calculatePurchaseOneProduct($product, $values['promocode']);

    if ($result) {
        $_SESSION['promocode_name'] = $values['promocode'];
        $_SESSION['promocode_discount'] = $result['calculationResult']['summary']['totalDiscount'];
        // $modx->log(xPDO::LOG_LEVEL_ERROR, 'Скидка по промокоду: '.$_SESSION['promocode_discount']);
        return true;
    }
}