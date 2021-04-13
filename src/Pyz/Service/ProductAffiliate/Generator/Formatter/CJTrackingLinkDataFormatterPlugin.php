<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

use Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException;
use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;

class CJTrackingLinkDataFormatterPlugin extends AbstractTrackingLinkDataFormatterPlugin
{
    protected const AFFILIATE_PARTNER_NAME = 'CJ';
    private const DEFAULT_NETWORK = 'CJ_AU';

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    protected function getUrl(string $productAffiliateDeepLink): string
    {
        return sprintf('%s&SID={TrackingHash}', $productAffiliateDeepLink);
    }

    /**
     * @return string
     */
    protected function getNetwork(): string
    {
        return self::DEFAULT_NETWORK;
    }

    /**
     * @param array $affiliateData
     *
     * @throws \Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException
     *
     * @return string
     */
    protected function getAdvertiserId(array $affiliateData): string
    {
        $affiliateMerchantId = $affiliateData[ProductAffiliateLinkGenerator::KEY_AFFILIATE_MERCHANT_ID] ?? null;
        if (!$affiliateMerchantId) {
            throw new ProductAffiliateTrackingLinkGeneratorException('Affiliate merchant ID missing.');
        }

        return $affiliateMerchantId;
    }
}
