<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductAffiliateOffersPriceWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_ABSTRACT_OFFERS_CLIENT = 'PRODUCT_ABSTRACT_OFFERS_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductAbstractOffersClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAbstractOffersClient(Container $container): Container
    {
        $container->set(
            static::PRODUCT_ABSTRACT_OFFERS_CLIENT,
            function (Container $container) {
                return $container->getLocator()->productAbstractOffers()->client();
            }
        );

        return $container;
    }
}
