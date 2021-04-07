<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Pyz\Service\ProductAffiliate\ProductAffiliateServiceFactory getFactory()
 */
class ProductAffiliateService extends AbstractService implements ProductAffiliateServiceInterface
{
    /**
     * {@inheritDoc}
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
    ): string {
        return $this->getFactory()->createGenerator()->generateTrackingUrl(
            $productAffiliateDeeplink,
            $affiliateNetwork,
            $customerTransfer
        );
    }
}
