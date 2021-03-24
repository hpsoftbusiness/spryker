<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

class WebgainsTrackingLinkDataFormatterPlugin extends AbstractTrackingLinkDataFormatterPlugin
{
    protected const AFFILIATE_PARTNER_NAME = 'WEBGAINS';
    protected const DEEPLINK_ADVERTISER_ID_PARAMETER = 'wgprogramid';

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    protected function getUrl(string $productAffiliateDeepLink): string
    {
        return sprintf('%s&clickRef={TrackingHash}', urldecode($productAffiliateDeepLink));
    }

    /**
     * @return string
     */
    protected function getNetwork(): string
    {
        return 'WEBGAINS';
    }
}
