<?php
// $stikProductRemains = $modx->getService('stikProductRemains','stikProductRemains', $modx->getOption('core_path').'components/stik/model/', []);
if ($modx->event->name == 'msOnBeforeAddToCart') {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'test');
        // $cart = $modx->getOption('cart', $scriptProperties, null);
        // $goods = $cart->get();
        // foreach($goods as &$good){
        //     if(count($good['options']) > 0){
        //         $remains = $stikProductRemains->getRemains(array_merge($good['options'],array('id'=>$good['id'],'strong'=>true)));
        //         $good['options']['max_count'] = $remains;
        //         if($remains < $good['count']){
        //             $good['count'] = $remains;
        //         }
        //     }
        // }
        // $cart->set($goods);
        break;
}