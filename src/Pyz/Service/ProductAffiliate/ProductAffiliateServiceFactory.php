<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate;

use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;
use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGeneratorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Pyz\Service\ProductAffiliate\ProductAffiliateConfig getConfig()
 */
class ProductAffiliateServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGeneratorInterface
     */
    public function createGenerator(): ProductAffiliateLinkGeneratorInterface
    {
        return new ProductAffiliateLinkGenerator($this->getConfig());
    }
}
