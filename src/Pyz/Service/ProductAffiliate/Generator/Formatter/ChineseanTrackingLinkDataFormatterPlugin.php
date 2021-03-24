<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

class ChineseanTrackingLinkDataFormatterPlugin extends AbstractTrackingLinkDataFormatterPlugin
{
    protected const AFFILIATE_PARTNER_NAME = 'CHINESEAN';
    protected const DEEPLINK_ADVERTISER_ID_PARAMETER = 'pId';
    private const DEFAULT_NETWORK = 'CHINESEAN_USD';

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    protected function getUrl(string $productAffiliateDeepLink): string
    {
        return sprintf('%s&mId={TrackingHash}', urldecode($productAffiliateDeepLink));
    }

    /**
     * @return string
     */
    protected function getNetwork(): string
    {
        return self::DEFAULT_NETWORK;
    }
}
