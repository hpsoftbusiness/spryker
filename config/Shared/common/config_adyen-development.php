<?php

use SprykerEco\Shared\Adyen\AdyenConstants;
use SprykerEco\Shared\AdyenApi\AdyenApiConstants;

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> ADYEN


$config[AdyenConstants::MERCHANT_ACCOUNT] = 'SprykerSystemsGmbHECOM';
$config[AdyenConstants::REQUEST_CHANNEL] = 'Web';
$config[AdyenConstants::SDK_CHECKOUT_SECURED_FIELDS_URL] = 'https://checkoutshopper-test.adyen.com/checkoutshopper/assets/js/sdk/checkoutSecuredFields.1.1.1.min.js';
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_JS_URL] = 'https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.9.4/adyen.js';
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_CSS_URL] = 'https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.9.4/adyen.css';
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_JS_INTEGRITY_HASH] = 'sha384-8Q8tz/+hf+UkS01nLrKLJgQLdaR1hRklqJQksCHh903UIfW+xMt275Lms4GZgVUi';
$config[AdyenConstants::SDK_CHECKOUT_SHOPPER_CSS_INTEGRITY_HASH] = 'sha384-6qrXvoxlnBlrflZQ9g5Yf5oZapUSSXctPxacP9oRcEukbEO7lXisuSyMKG8pDX8V';
$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = 'pub.v2.8216060629003813.aHR0cDovL3l2ZXMuZGUubXl3b3JsZC5sb2NhbA.vGlSBdB3YtYaGaWAVqJ6_iGkldlIAxfxPcL54NmoW88';
$config[AdyenConstants::SDK_ENVIRONMENT] = 'test';
$config[AdyenConstants::SOFORT_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-sofort';
$config[AdyenConstants::CREDIT_CARD_3D_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-credit-card-3d';
$config[AdyenConstants::IDEAL_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-ideal';
$config[AdyenConstants::PAY_PAL_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-paypal';
$config[AdyenConstants::ALI_PAY_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-alipay';
$config[AdyenConstants::WE_CHAT_PAY_RETURN_URL] = 'https://yves.de.myworld.local/adyen/callback/redirect-wechatpay';
$config[AdyenConstants::CREDIT_CARD_3D_SECURE_ENABLED] = true;
$config[AdyenConstants::MULTIPLE_PARTIAL_CAPTURE_ENABLED] = false;
$config[AdyenConstants::SOCIAL_SECURITY_NUMBER_COUNTRIES_MANDATORY] = [
    'SE',
    'FI',
    'NO',
    'DK',
];
$config[AdyenConstants::IDEAL_ISSUERS_LIST] = [
    '1121' => 'Test Issuer',
    '1151' => 'Test Issuer 2',
    '1152' => 'Test Issuer 3',
    '1153' => 'Test Issuer 4',
    '1154' => 'Test Issuer 5',
    '1155' => 'Test Issuer 6',
    '1156' => 'Test Issuer 7',
    '1157' => 'Test Issuer 8',
    '1158' => 'Test Issuer 9',
    '1159' => 'Test Issuer 10',
    '1160' => 'Test Issuer Refused',
    '1161' => 'Test Issuer Pending',
    '1162' => 'Test Issuer Cancelled',
];
$config[AdyenApiConstants::API_KEY] = 'AQE0hmfxJo3GbxVFw0m/n3Q5qf3Ve5tfFJhPSlZP1Wyuk3Vsk8VeFIIVBDMhO7Dt/FfRk5gTPRDBXVsNvuR83LVYjEgiTGAH-Ch84VbVgtIcablapxRlRFV5XKJ26G55L4sEuSt7oF88=-=Q<C753Kg]%6eC>$';
$config[AdyenApiConstants::GET_PAYMENT_METHODS_ACTION_URL] = 'https://checkout-test.adyen.com/v32/paymentMethods';
$config[AdyenApiConstants::MAKE_PAYMENT_ACTION_URL] = 'https://checkout-test.adyen.com/v32/payments';
$config[AdyenApiConstants::PAYMENTS_DETAILS_ACTION_URL] = 'https://checkout-test.adyen.com/v32/payments/details';
$config[AdyenApiConstants::AUTHORIZE_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/authorise';
$config[AdyenApiConstants::AUTHORIZE_3D_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/authorise3d';
$config[AdyenApiConstants::CAPTURE_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/capture';
$config[AdyenApiConstants::CANCEL_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/cancel';
$config[AdyenApiConstants::REFUND_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/refund';
$config[AdyenApiConstants::CANCEL_OR_REFUND_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/cancelOrRefund';
$config[AdyenApiConstants::TECHNICAL_CANCEL_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/technicalCancel';
$config[AdyenApiConstants::ADJUST_AUTHORIZATION_ACTION_URL] = 'https://pal-test.adyen.com/pal/servlet/Payment/v30/adjustAuthorisation';
