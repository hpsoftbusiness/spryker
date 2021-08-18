<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer;

use Pyz\Shared\Sso\SsoConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Customer\CustomerConfig as SprykerCustomerConfig;

class CustomerConfig extends SprykerCustomerConfig
{
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getCustomerDetailExternalBlocksUrls()
    {
        return [
                'sales' => '/sales/customer/customer-orders',
                'notes' => '/customer-note-gui/index/index',
            ] + parent::getCustomerDetailExternalBlocksUrls();
    }

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool
    {
        return $this->get(SsoConstants::SSO_LOGIN_ENABLED, false);
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return Store::getInstance()->getStoreName();
    }
}
