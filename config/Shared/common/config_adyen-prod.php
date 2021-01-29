<?php

use Pyz\Shared\Adyen\AdyenConstants;
use Spryker\Shared\Application\ApplicationConstants;
use SprykerEco\Shared\AdyenApi\AdyenApiConstants;

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> ADYEN
// TODO: move credentials to ENV variables
$adyenCredentials =  [
    'CHECKOUT_SHOPPER_API_DOMAIN' => 'checkoutshopper-live.adyen.com',
    'CHECKOUT_SHOPPER_API_VERSION' => '3.9.4',
    'JS_INTEGRITY_HASH' => utf8_encode('sha384-8Q8tz/+hf+UkS01nLrKLJgQLdaR1hRklqJQksCHh903UIfW+xMt275Lms4GZgVUi'),
    'CSS_INTEGRITY_HASH' => utf8_encode('sha384-6qrXvoxlnBlrflZQ9g5Yf5oZapUSSXctPxacP9oRcEukbEO7lXisuSyMKG8pDX8V'),
    'ORIGIN_KEY' => utf8_encode('pub.v2.4116119410741591.aHR0cHM6Ly93d3cubWFya2V0cGxhY2UubXl3b3JsZC5jb20.4WwTYl6NQnAxJJ_N3OmG10fBr6W-UMkOT9JvngAz6EY'),
    'API_KEY' => utf8_encode('AQEphmfxJ4/MahNHw0m/n3Q5qf3VZZJ6AoFGXFyYtVLiei4j0mpavMEUWLsQwV1bDb7kfNy1WIxIIkxgBw==-wTOOSt8wx4hz9o1lHYqyW6HTuMlLQL+XgMvUYcXmPlo=-}]]7;Fz:8B(4xu^h'),
    'CHECKOUT_API_DOMAIN' => '1c8a29fafebad310-MyWorld-checkout-live.adyenpayments.com',
    'CHECKOUT_API_VERSION' => 'v37',
    'PAYMENT_API_DOMAIN' => '1c8a29fafebad310-MyWorld-pal-live.adyenpayments.com',
    'PAYMENT_API_VERSION' => 'v30',
    'MERCHANT_ACCOUNT' => 'MyWorldAustriaGmbH',
    'SDK_ENVIRONMENT' => 'live',
];

$config[AdyenConstants::SPLIT_ACCOUNT] = '125549301';
$config[AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST] = 0.015;

$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNT'];
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_JS_URL] = sprintf(
    'https://%s/checkoutshopper/sdk/%s/adyen.js',
    $adyenCredentials['CHECKOUT_SHOPPER_API_DOMAIN'],
    $adyenCredentials['CHECKOUT_SHOPPER_API_VERSION'],
);
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_CSS_URL] = sprintf(
    'https://%s/checkoutshopper/sdk/%s/adyen.css',
    $adyenCredentials['CHECKOUT_SHOPPER_API_DOMAIN'],
    $adyenCredentials['CHECKOUT_SHOPPER_API_VERSION'],
);
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_JS_INTEGRITY_HASH] = utf8_decode($adyenCredentials['JS_INTEGRITY_HASH']);
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_CSS_INTEGRITY_HASH] = utf8_decode($adyenCredentials['CSS_INTEGRITY_HASH']);
$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = utf8_decode($adyenCredentials['ORIGIN_KEY']);
$config[AdyenConstants::REQUEST_CHANNEL] = 'Web';
$config[AdyenConstants::SDK_CHECKOUT_SECURED_FIELDS_URL] = '';
$config[AdyenConstants::SDK_ENVIRONMENT] = $adyenCredentials['SDK_ENVIRONMENT'];
$config[AdyenConstants::CREDIT_CARD_3D_RETURN_URL] = sprintf(
    '%s/adyen/callback/redirect-credit-card-3d',
    $config[ApplicationConstants::BASE_URL_YVES]
);
$config[AdyenConstants::CREDIT_CARD_3D_SECURE_ENABLED] = true;
$config[AdyenConstants::MULTIPLE_PARTIAL_CAPTURE_ENABLED] = false;

$config[AdyenApiConstants::API_KEY] = utf8_decode($adyenCredentials['API_KEY']);
$config[AdyenApiConstants::GET_PAYMENT_METHODS_ACTION_URL] = sprintf(
    'https://%s/%s/paymentMethods',
    $adyenCredentials['CHECKOUT_API_DOMAIN'],
    $adyenCredentials['CHECKOUT_API_VERSION']
);
$config[AdyenApiConstants::MAKE_PAYMENT_ACTION_URL] = sprintf(
    'https://%s/%s/payments',
    $adyenCredentials['CHECKOUT_API_DOMAIN'],
    $adyenCredentials['CHECKOUT_API_VERSION']
);
$config[AdyenApiConstants::PAYMENTS_DETAILS_ACTION_URL] = sprintf(
    'https://%s/%s/payments/details',
    $adyenCredentials['CHECKOUT_API_DOMAIN'],
    $adyenCredentials['CHECKOUT_API_VERSION']
);
$config[AdyenApiConstants::AUTHORIZE_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/authorise',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::AUTHORIZE_3D_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/authorise3d',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::CAPTURE_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/capture',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::CANCEL_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/cancel',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::REFUND_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/refund',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::CANCEL_OR_REFUND_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/cancelOrRefund',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::TECHNICAL_CANCEL_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/technicalCancel',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
$config[AdyenApiConstants::ADJUST_AUTHORIZATION_ACTION_URL] = sprintf(
    'https://%s/pal/servlet/Payment/%s/adjustAuthorisation',
    $adyenCredentials['PAYMENT_API_DOMAIN'],
    $adyenCredentials['PAYMENT_API_VERSION']
);
