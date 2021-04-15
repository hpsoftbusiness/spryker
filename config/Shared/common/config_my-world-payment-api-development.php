<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

// ----------------------------------------------------------------------------
// -------------------------- MyWorldPaymentApi -------------------------------
// ----------------------------------------------------------------------------

// >>> MyWorld PSP


use Pyz\Shared\MyWorldPaymentApi\MyWorldPaymentApiConstants;

$config[MyWorldPaymentApiConstants::API_HOST] = 'https://preprod-payments-api.myworldwebservices.com';
$config[MyWorldPaymentApiConstants::API_KEY_ID] = getenv('MY_WORLD_PAYMENT_API_KEY_ID') ?: 'spryker_at_dev';
$config[MyWorldPaymentApiConstants::API_KEY_SECRET] = getenv('MY_WORLD_PAYMENT_API_KEY_SECRET') ?: 'spryker_at_dev';

$config[MyWorldPaymentApiConstants::PAYMENT_SESSION_ACTION_URI] = $config[MyWorldPaymentApiConstants::API_HOST] . '/sessions';
$config[MyWorldPaymentApiConstants::PAYMENT_ACTION_URI] = $config[MyWorldPaymentApiConstants::API_HOST] . '/payments';
