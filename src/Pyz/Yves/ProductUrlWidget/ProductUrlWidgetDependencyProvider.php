<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductUrlWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const SERVICE_PRODUCT_AFFILIATE = 'SERVICE_PRODUCT_AFFILIATE';
    public const PRODUCT_ABSTRACT_OFFERS_CLIENT = 'PRODUCT_ABSTRACT_OFFERS_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addProductAffiliateService($container);
        $container = $this->addProductAbstractOffersClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(
            static::CLIENT_CUSTOMER,
            function (Container $container) {
                return $container->getLocator()->customer()->client();
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAffiliateService(Container $container): Container
    {
        $container->set(
            static::SERVICE_PRODUCT_AFFILIATE,
            function (Container $container) {
                return $container->getLocator()->productAffiliate()->service();
            }
        );

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
