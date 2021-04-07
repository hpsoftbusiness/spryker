<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException;
use Pyz\Service\ProductAffiliate\Generator\Formatter\TrackingLinkDataFormatterPluginInterface;
use Pyz\Service\ProductAffiliate\ProductAffiliateConfig;

class ProductAffiliateLinkGenerator implements ProductAffiliateLinkGeneratorInterface
{
    /**
     * @var \Pyz\Service\ProductAffiliate\ProductAffiliateConfig
     */
    private $productAffiliateConfig;

    /**
     * @var \Pyz\Service\ProductAffiliate\Generator\Formatter\TrackingLinkDataFormatterPluginInterface[]
     */
    private $formatterPlugins;

    /**
     * @param \Pyz\Service\ProductAffiliate\ProductAffiliateConfig $productAffiliateConfig
     * @param \Pyz\Service\ProductAffiliate\Generator\Formatter\TrackingLinkDataFormatterPluginInterface[] $formatterPlugins
     */
    public function __construct(
        ProductAffiliateConfig $productAffiliateConfig,
        array $formatterPlugins
    ) {
        $this->productAffiliateConfig = $productAffiliateConfig;
        $this->formatterPlugins = $formatterPlugins;
    }

    /**
     * @param string $productAffiliateDeeplink
     * @param string $affiliateNetwork
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function generateTrackingUrl(
        string $productAffiliateDeeplink,
        string $affiliateNetwork,
        CustomerTransfer $customerTransfer
    ): string {
        $trackingLinkDataFormatterPlugin = $this->getTrackingLinkFormatterPlugin($affiliateNetwork);
        $trackingUrlArguments = $trackingLinkDataFormatterPlugin->getFormattedTrackingLinkData(
            $productAffiliateDeeplink,
            $customerTransfer
        );

        return sprintf(
            '%s?%s',
            $this->productAffiliateConfig->getTrackingUrlPath(),
            http_build_query($trackingUrlArguments, '', '&')
        );
    }

    /**
     * @param string $affiliateNetwork
     *
     * @return \Pyz\Service\ProductAffiliate\Generator\Formatter\TrackingLinkDataFormatterPluginInterface
     *@throws \Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException
     *
     */
    private function getTrackingLinkFormatterPlugin(string $affiliateNetwork): TrackingLinkDataFormatterPluginInterface
    {
        foreach ($this->formatterPlugins as $dataFormatterPlugin) {
            if (strtoupper($dataFormatterPlugin->getApplicableAffiliatePartnerName()) === strtoupper($affiliateNetwork)) {
                return $dataFormatterPlugin;
            }
        }

        throw new ProductAffiliateTrackingLinkGeneratorException('Tracking link data formatter plugin not found for this affiliate partner.');
    }
}
