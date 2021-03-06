<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi;

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MyWorldMarketplaceApiConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::API_URL);
    }

    /**
     * @return string
     */
    public function getDealerIdDefault(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT);
    }

    /**
     * @return string[]
     */
    public function getDealerIdCountryMap(): array
    {
        return $this->get(MyWorldMarketplaceApiConstants::DEALER_ID_COUNTRY_MAP);
    }

    /**
     * @return string
     */
    public function getOrderReferencePrefix(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX);
    }
}
