<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp;

use Pyz\Shared\Weclapp\WeclappConstants;
use Spryker\Client\Kernel\AbstractBundleConfig;

class WeclappConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->get(WeclappConstants::API_URL);
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->get(WeclappConstants::API_TOKEN);
    }

    /**
     * @return string
     */
    public function getCustomAttributeKeyMyWorldCustomerId(): string
    {
        return $this->get(WeclappConstants::CUSTOM_ATTRIBUTE_KEY_MY_WORLD_CUSTOMER_ID);
    }

    /**
     * @return string
     */
    public function getCustomAttributeKeyCashbackId(): string
    {
        return $this->get(WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_ID);
    }

    /**
     * @return string
     */
    public function getCustomAttributeKeyCashbackCardNumber(): string
    {
        return $this->get(WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_CARD_NUMBER);
    }
}
