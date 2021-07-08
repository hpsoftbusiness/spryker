<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer;

use Pyz\Shared\Sso\SsoConstants;
use Spryker\Client\Customer\CustomerConfig as SprykerCustomerConfig;

class CustomerConfig extends SprykerCustomerConfig
{
    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool
    {
        return $this->get(SsoConstants::SSO_LOGIN_ENABLED, false);
    }
}
