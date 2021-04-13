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
    public const KEY_AFFILIATE_DEEPLINK = 'affiliate_deeplink';
    public const KEY_AFFILIATE_NETWORK = 'affiliate_network';
    public const KEY_AFFILIATE_MERCHANT_ID = 'affiliate_merchant_id';

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
     * @param \Generated\Shared\Transfer\CustomerTransfer|array $customerTransfer
     * @param array $affiliateData
     *
     * @return string
     */
    public function generateTrackingUrl(
        array $affiliateData,
        CustomerTransfer $customerTransfer
    ): string {
        try {
            /**
             * @TODO change fallback to null when product import files are updated with 'affiliate_network' attribute
             */
            $affiliateNetwork = $affiliateData[self::KEY_AFFILIATE_NETWORK] ?? 'AWIN';
            $trackingLinkDataFormatterPlugin = $this->getTrackingLinkFormatterPlugin($affiliateNetwork);
            $trackingUrlArguments = $trackingLinkDataFormatterPlugin->getFormattedTrackingLinkData(
                $affiliateData,
                $customerTransfer
            );

            return sprintf(
                '%s?%s',
                $this->productAffiliateConfig->getTrackingUrlPath(),
                http_build_query($trackingUrlArguments, '', '&')
            );
        } catch (ProductAffiliateTrackingLinkGeneratorException $exception) {
            return $affiliateData[self::KEY_AFFILIATE_DEEPLINK];
        }
    }

    /**
     * @param string $affiliateNetwork
     *
     * @throws \Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException
     *
     * @return \Pyz\Service\ProductAffiliate\Generator\Formatter\TrackingLinkDataFormatterPluginInterface
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
