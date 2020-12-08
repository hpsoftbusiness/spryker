<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAffiliate\Business;

use Pyz\Zed\ProductAffiliate\Business\Checker\ProductAffiliateChecker;
use Pyz\Zed\ProductAffiliate\Business\Checker\ProductAffiliateCheckerInterface;
use Pyz\Zed\ProductAffiliate\ProductAffiliateDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\ProductFacadeInterface;

class ProductAffiliateBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductAffiliate\Business\Checker\ProductAffiliateCheckerInterface
     */
    public function createProductAffiliateChecker(): ProductAffiliateCheckerInterface
    {
        return new ProductAffiliateChecker(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductAffiliateDependencyProvider::FACADE_PRODUCT);
    }
}
