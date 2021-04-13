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
     * - Generate a product affiliate tracking url using provided customer data and product affiliate data.
     *
     * @api
     *
     * @param array $affiliateData
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function generateProductAffiliateTrackingUrl(
        array $affiliateData,
        CustomerTransfer $customerTransfer
    ): string;
}
