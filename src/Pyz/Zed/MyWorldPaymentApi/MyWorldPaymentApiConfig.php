<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi;

use Pyz\Shared\MyWorldPaymentApi\MyWorldPaymentApiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MyWorldPaymentApiConfig extends AbstractBundleConfig
{
    public const PAYMENT_STATUS_CHARGED = 'Charged';
    public const PAYMENT_STATUS_FAILED = 'Failed';

    public const PAYMENT_TRANSACTION_STATUS_CODE_ACCEPTED = 0;
    public const PAYMENT_TRANSACTION_STATUS_CODE_IN_PROGRESS = 1;
    public const PAYMENT_TRANSACTION_STATUS_CODE_FAILED = 100;
    public const PAYMENT_TRANSACTION_STATUS_CODE_EXCEEDED_VALUE = 101;
    public const PAYMENT_TRANSACTION_STATUS_CODE_BATCH_FAILED = 102;
    public const PAYMENT_TRANSACTION_STATUS_CODE_OPTION_NOT_FOUND = 103;

    /**
     * @return string
     */
    public function getApiKeyId(): string
    {
        return $this->get(MyWorldPaymentApiConstants::API_KEY_ID);
    }

    /**
     * @return string
     */
    public function getSecretApiKey(): string
    {
        return $this->get(MyWorldPaymentApiConstants::API_KEY_SECRET);
    }

    /**
     * @return string
     */
    public function getPaymentSessionActionUri(): string
    {
        return $this->get(MyWorldPaymentApiConstants::PAYMENT_SESSION_ACTION_URI);
    }

    /**
     * @return string
     */
    public function getPaymentActionUrl(): string
    {
        return $this->get(MyWorldPaymentApiConstants::PAYMENT_ACTION_URI);
    }
}
