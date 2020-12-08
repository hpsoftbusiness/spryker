<?php

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
    public function getDealerId(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::DEALER_ID);
    }

    /**
     * @return string
     */
    public function getOrderReferencePrefix(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX);
    }
}
