<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Spryker\Client\Kernel\AbstractBundleConfig;

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
    public function getTokenUrl(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::TOKEN_URL);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::USER_AGENT);
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->get(MyWorldMarketplaceApiConstants::SCOPE);
    }
}
