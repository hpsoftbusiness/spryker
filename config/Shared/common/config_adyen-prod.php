<?php

use Spryker\Shared\Application\ApplicationConstants;
use SprykerEco\Shared\Adyen\AdyenConstants;
use SprykerEco\Shared\AdyenApi\AdyenApiConstants;

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> ADYEN

//TODO Set correct variables to env variables.
//$adyenCredentials = json_decode(getenv('SPRYKER_ADYEN_CREDENTIALS') ?: 'null', true);

$adyenCredentials = [
    'CHECKOUT_SHOPPER_API_DOMAIN' => 'checkoutshopper-test.adyen.com',
    'CHECKOUT_SHOPPER_API_VERSION' => '3.9.4',
    'JS_INTEGRITY_HASH' => utf8_encode('sha384-8Q8tz/+hf+UkS01nLrKLJgQLdaR1hRklqJQksCHh903UIfW+xMt275Lms4GZgVUi'),
    'CSS_INTEGRITY_HASH' => utf8_encode('sha384-6qrXvoxlnBlrflZQ9g5Yf5oZapUSSXctPxacP9oRcEukbEO7lXisuSyMKG8pDX8V'),
    'ORIGIN_KEY' => utf8_encode('pub.v2.8216077771133800.aHR0cHM6Ly93d3cubWFya2V0cGxhY2UubXl3b3JsZC5jb20.8z6eTHoPvxXIV5CgwSmx8Xs3Vou6UNDQwKNh9gzPv2o'),
    'API_KEY' => utf8_encode('AQEphmfxJonJbR1Ew0m/n3Q5qf3VZZJ6AoFGXIkZ3GbdhshSfDBbFwxy9eAQwV1bDb7kfNy1WIxIIkxgBw==-v7XhXNSuBcVqAkX7VDrHgT+Kv1qOJbXBq+JY3fv/3as=-IQKyS~7yQ_v&;SqY'),
    'CHECKOUT_API_DOMAIN' => 'checkout-test.adyen.com',
    'CHECKOUT_API_VERSION' => 'v32',
    'PAYMENT_API_DOMAIN' => 'pal-test.adyen.com',
    'PAYMENT_API_VERSION' => 'v30',
    'MERCHANT_ACCOUNT' => 'MyWorldInternationalLimited',
    'SDK_ENVIRONMENT' => 'test',
];

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
