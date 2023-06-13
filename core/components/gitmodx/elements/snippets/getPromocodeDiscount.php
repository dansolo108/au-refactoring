<?php

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' || empty($_POST['action'])) {return;}

header('Content-Type: application/json; charset=utf-8');

$response = [];

switch ($_POST['action']) {
	case 'checkpromocode':

    $promocode = $_POST['promocode'];

    if (empty($promocode)) {
      $response = [
        'success' => false,
        'message' => 'Поле промокода не заполнено'
      ];
      die($json_encode($response));
    }

    $maxmaPath = MODX_CORE_PATH . 'components/modmaxma/model/';
    $modx->loadClass('modMaxma', $maxmaPath, true, true);
    $maxma = new modMaxma($modx);

    if (!($maxma instanceof modMaxma)) {
      $response = [
        'success' => false,
        'message' => 'Что-то пошло не так. Попробуйте позже'
      ];
      die($json_encode($response));
    }

    $tmp = explode(',', $_POST['product']);

    if (count($tmp) == 2 && is_numeric($tmp[1])) {
        $product = [
            'sku' => $tmp[0],
            'price' => $tmp[1],
        ];
        $result = $maxma->calculatePurchaseOneProduct($product, $promocode);

        if ($result) {
          // $response = $result;
          $response = [
            'success' => true,
            'message' => 'Промокод применен',
            'discount' => $result['calculationResult']['summary']['totalDiscount']
          ];
        }
    }
		break;
  case 'cancelpromocode':
    $response = [
      'success' => true,
      'message' => 'Промокод отменен'
    ];
    break;
}

if (!empty($response)) {
  
  echo json_encode($response);
}