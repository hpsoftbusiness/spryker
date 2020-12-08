<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Service\ProductAffiliate\ProductAffiliateConfig;

class ProductAffiliateLinkGenerator implements ProductAffiliateLinkGeneratorInterface
{
    /**
     * @var \Pyz\Service\ProductAffiliate\ProductAffiliateConfig
     */
    protected $productAffiliateConfig;

    /**
     * @param \Pyz\Service\ProductAffiliate\ProductAffiliateConfig $productAffiliateConfig
     */
    public function __construct(ProductAffiliateConfig $productAffiliateConfig)
    {
        $this->productAffiliateConfig = $productAffiliateConfig;
    }

    /**
     * @param string $productAffiliateDeeplink
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function generateTrackingUrl(
        string $productAffiliateDeeplink,
        CustomerTransfer $customerTransfer
    ): string {
        $deeplinkQueryString = parse_url($productAffiliateDeeplink, PHP_URL_QUERY);
        parse_str($deeplinkQueryString, $deeplinkArguments);

        $trackingUrlArguments = [
            'customerNumber' => $customerTransfer->getMyWorldCustomerNumber(),
            'network' => $this->productAffiliateConfig->getTrackingUrlNetworkArgument(),
            'AdvertiserId' => $deeplinkArguments['m'],
            'url' => sprintf('%s&clickRef=trackingHash', $productAffiliateDeeplink),
        ];

        return sprintf(
            '%s?%s',
            $this->productAffiliateConfig->getTrackingUrlPath(),
            http_build_query($trackingUrlArguments, '', '&')
        );
    }
}
