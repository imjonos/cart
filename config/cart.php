<?php

return [
    'model' => CodersStudio\Cart\Models\Item::class, //Model of Item Element
    'product_model' => CodersStudio\Cart\Models\Product::class,
    'purchased_product_model' => CodersStudio\Cart\Models\PurchasedProduct::class,
    'drivers' => [
        'card' => CodersStudio\Cart\Drivers\CreditCardPaymentDriver::class,
        'paypal' => CodersStudio\Cart\Drivers\PaypalPaymentDriver::class,
    ],
];
