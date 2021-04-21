<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\MyWorldMarketplaceApi;

use Pyz\Shared\Sso\SsoConstants;
use Spryker\Shared\Kernel\AbstractSharedConfig;

class MyWorldMarketplaceApiConfig extends AbstractSharedConfig
{
    /**
     * @return string
     */
    public function getCustomerBalanceByCurrencyUrl(): string
    {
        return $this->get(SsoConstants::CUSTOMER_ACCOUNT_BALANCE_BY_CURRENCY_URL);
    }
}
