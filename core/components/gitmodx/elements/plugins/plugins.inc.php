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
    'SMSBeforeCodeSend' => [
        'auth'
    ],
    'SMSAfterCodeSend' => [
        'auth'
    ],
    'SMSBeforeCodeCheck' => [
        'auth'
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
    'msOnChangeOrderStatus' => [
        'certificate'
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
    'msOnValidateOrderValue' => [
        'ms2validate'
    ],
);