<?php
return array(
    'OnDocFormPrerender' => [
        'autentiments'
    ],
    'OnLoadWebDocument' => [
        'registerFrontVariables',
    ],
    'msOnBeforeCreateOrder' => [
        'roistat'
    ],
    'mSyncOnPrepareProduct' => [
        'mSyncModifications'
    ],
    'mSyncOnProductImport' => [
        'mSyncModifications'
    ],
    'mSyncOnProductOffers' => [
        'mSyncModifications'
    ],
    'mSyncOnSalesExport' => [
        'mSyncModifications'
    ],
    'SMSAfterCodeCheck' => [
        'auth'
    ],
    'msOnCreateOrder' => [
        'loginAndRegister',
        'ModificationsRemains'
    ],
    'msOnBeforeGetOrderCustomer' => [
        'loginAndRegister'
    ],
    'OnUserSave' => [
        'loginAndRegister'
    ],
    'OnModificationRemainsUpdate' => [
        'subscribersSend'
    ],
    'msOnAddToCart' => [
        'refreshPrices'
    ],
    'msOnChangeInCart' => [
        'refreshPrices'
    ],
    'msOnGetStatusCart' => [
        'refreshPrices',
        "realTotalCost"
    ],
    'msOnRemoveFromCart' => [
        'refreshPrices'
    ],
    'msOnRemoveFromCart' => [
        'refreshPrices'
    ],
    'msOnValidateOrderValue' => [
        'ms2validate'
    ],
);