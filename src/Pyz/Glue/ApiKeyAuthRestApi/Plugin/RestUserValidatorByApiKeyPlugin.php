<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ApiKeyAuthRestApi\Plugin;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class RestUserValidatorByApiKeyPlugin extends AbstractPlugin implements RestUserValidatorPluginInterface
{
    protected const RESTRICTED_RESOURCES = [
        ProductFeedRestApiConfig::RESOURCE_REGULAR_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_BENEFIT_VOUCHER_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_SHOPPING_POINT_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_ELITE_CLUB_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_ONE_SENSE_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_LYCONET_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_FEATURED_PRODUCT_FEED,
        ProductFeedRestApiConfig::RESOURCE_ONLY_ELITE_CLUB_DEAL_PRODUCT_FEED,
    ];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if ($this->isRestrictedResource($restRequest)
            && !$this->isAuthenticatedByApiKey($restRequest)
        ) {
            return (new RestErrorMessageTransfer())
                ->setStatus(500);
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    private function isAuthenticatedByApiKey(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getRestUser() && $restRequest->getRestUser()->getIsAuthenticatedByApiKey() === true;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    private function isRestrictedResource(RestRequestInterface $restRequest): bool
    {
        return in_array($restRequest->getResource()->getType(), static::RESTRICTED_RESOURCES);
    }
}
