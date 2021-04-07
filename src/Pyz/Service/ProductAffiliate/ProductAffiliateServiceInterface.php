<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate;

use Generated\Shared\Transfer\CustomerTransfer;

interface ProductAffiliateServiceInterface
{
    /**
     * Specification:
     * - Generate a product affiliate tracking url using provided customer data and product affiliate deeplink.
     *
     * @param string $productAffiliateDeeplink
     * @param string $affiliateNetwork
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     *@api
     *
     */
    public function generateProductAffiliateTrackingUrl(
        string $productAffiliateDeeplink,
        string $affiliateNetwork,
        CustomerTransfer $customerTransfer
    ): string;
}
