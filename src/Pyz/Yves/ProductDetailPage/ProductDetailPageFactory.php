<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage;

use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Client\ProductAttribute\ProductAttributeClientInterface;
use Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface;
use SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory as SprykerShopProductDetailPageFactory;

/**
 * @method \Pyz\Yves\ProductDetailPage\ProductDetailPageConfig getConfig()
 */
class ProductDetailPageFactory extends SprykerShopProductDetailPageFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface
     */
    public function getProductAffiliateService(): ProductAffiliateServiceInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::SERVICE_PRODUCT_AFFILIATE);
    }

    /**
     * @return \Pyz\Client\ProductAttribute\ProductAttributeClientInterface
     */
    public function getProductAttributeClient(): ProductAttributeClientInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::CLIENT_PRODUCT_ATTRIBUTE);
    }
}
