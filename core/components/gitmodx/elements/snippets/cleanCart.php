<?php

if ($miniShop2 = $modx->getService('miniShop2')) {
    $miniShop2->initialize($modx->context->key);
    $miniShop2->cart->clean();
}