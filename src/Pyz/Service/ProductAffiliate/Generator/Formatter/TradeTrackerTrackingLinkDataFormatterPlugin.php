<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

class TradeTrackerTrackingLinkDataFormatterPlugin extends AbstractTrackingLinkDataFormatterPlugin
{
    protected const AFFILIATE_PARTNER_NAME = 'TRADETRACKER';
    protected const DEEPLINK_ADVERTISER_ID_PARAMETER = 'c';

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    protected function getUrl(string $productAffiliateDeepLink): string
    {
        return sprintf('%s&r={TrackingHash}', urldecode($productAffiliateDeepLink));
    }

    /**
     * @return string
     */
    protected function getNetwork(): string
    {
        return 'TT_CH';
    }
}