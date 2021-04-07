<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator;

use Generated\Shared\Transfer\CustomerTransfer;

interface ProductAffiliateLinkGeneratorInterface
{
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
    ): string;
}
