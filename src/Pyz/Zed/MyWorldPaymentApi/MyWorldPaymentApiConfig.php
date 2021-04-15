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
