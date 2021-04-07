<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

use Generated\Shared\Transfer\CustomerTransfer;

interface TrackingLinkDataFormatterPluginInterface
{
    /**
     * @param string $productAffiliateDeeplink
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getFormattedTrackingLinkData(
        string $productAffiliateDeeplink,
        CustomerTransfer $customerTransfer
    ): array;

    /**
     * @return string
     */
    public function getApplicableAffiliatePartnerName(): string;
}
