<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget;

use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Client\ProductAbstractOffers\ProductAbstractOffersClientInterface;
use Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface;
use Pyz\Yves\ProductDetailPage\ProductDetailPageDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductUrlWidgetFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(ProductUrlWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface
     */
    public function getProductAffiliateService(): ProductAffiliateServiceInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::SERVICE_PRODUCT_AFFILIATE);
    }

    /**
     * @return \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersClientInterface
     */
    public function getProductAbstractOffersClient(): ProductAbstractOffersClientInterface
    {
        return $this->getProvidedDependency(
            ProductUrlWidgetDependencyProvider::PRODUCT_ABSTRACT_OFFERS_CLIENT
        );
    }
}
