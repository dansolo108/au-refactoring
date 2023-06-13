<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

$response = [];

switch ($_POST['step']) {
	case 'check':

    $promocode = $_POST['promocode'];

    if (empty($promocode)) {
      $response = [
        'success' => false,
        'message' => 'Поле промокода не заполнено'
      ];
      $json = json_encode($response, JSON_PRETTY_PRINT);
      return $json;
    }

    $maxmaPath = MODX_CORE_PATH . 'components/modmaxma/model/';
    $modx->loadClass('modMaxma', $maxmaPath, true, true);
    $maxma = new modMaxma($modx);

    if (!($maxma instanceof modMaxma)) {
      $response = [
        'success' => false,
        'message' => 'Что-то пошло не так. Попробуйте позже'
      ];
      $json = json_encode($response, JSON_PRETTY_PRINT);
      return $json;
    }

    $tmp = explode(',', $_POST['product']);

    if (count($tmp) == 2 && is_numeric($tmp[1])) {
        $product = [
            'sku' => $tmp[0],
            'price' => $tmp[1],
        ];
        $result = $maxma->calculatePurchaseOneProduct($product, $promocode);

        if ($result) {
          if ($result['calculationResult']['promocode']['applied']) {
            $response = [
              'success' => true,
              'message' => 'Промокод применен',
              'discount' => $result['calculationResult']['summary']['totalDiscount']
            ];
          } else {
            $response = [
              'success' => false,
              'message' => 'Промокод не найден',
            ];
          }
        }
    }
		break;
  case 'cancel':
    $response = [
      'success' => true,
      'message' => 'Промокод отменен'
    ];
    break;
}

if (!empty($response)) {
  $json = json_encode($response, JSON_PRETTY_PRINT);
  return $json;
}

return '{}';