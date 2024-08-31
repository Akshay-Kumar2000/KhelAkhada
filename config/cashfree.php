<?php

return [
    //These are for the Marketplace
    'appID' => '',
    'secretKey' => '',
    'testURL' => 'https://ces-gamma.cashfree.com',
    'prodURL' => 'https://ces-api.cashfree.com',
    'maxReturn' => 100, //this is for request pagination
    'isLive' => false,

    //For the PaymentGateway.
    'PG' => [
        'appID' => '102870dabd9c652d5d7dfbcff8078105',      //Test
        'secretKey' => '840b8e9a2b18aa337960cd8adf6f1c56188860a9',      //Test        
        'testURL' => 'https://test.cashfree.com',
        'prodURL' => 'https://api.cashfree.com',
        'isLive' => true
    ]
];
