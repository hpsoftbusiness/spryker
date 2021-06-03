<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersWidget;

use Pyz\Client\ProductAbstractOffers\ProductAbstractOffersClientInterface;
use Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProvider;
use Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProviderInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductAffiliateOffersWidgetFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProviderInterface
     */
    public function createAffiliateDataProvider(): AffiliateDataProviderInterface
    {
        return new AffiliateDataProvider();
    }

    /**
     * @return \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersClientInterface
     */
    public function getProductAbstractOffersClient(): ProductAbstractOffersClientInterface
    {
        return $this->getProvidedDependency(
            ProductAffiliateOffersWidgetDependencyProvider::PRODUCT_ABSTRACT_OFFERS_CLIENT
        );
    }
}
