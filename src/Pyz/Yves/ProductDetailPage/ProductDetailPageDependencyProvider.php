<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage;

use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDetailPage\ProductDetailPageDependencyProvider as SprykerShopProductDetailPageDependencyProvider;

class ProductDetailPageDependencyProvider extends SprykerShopProductDetailPageDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_PRODUCT_ATTRIBUTE = 'CLIENT_PRODUCT_ATTRIBUTE';

    public const SERVICE_PRODUCT_AFFILIATE = 'SERVICE_PRODUCT_AFFILIATE';

    public const CLIENT_SSO = 'CLIENT_SSO';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addProductAffiliateService($container);
        $container = $this->addProductAttributeClient($container);
        $container = $this->addSsoClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return $container->getLocator()->customer()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAffiliateService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_AFFILIATE, function (Container $container) {
            return $container->getLocator()->productAffiliate()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAttributeClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_ATTRIBUTE, function (Container $container) {
            return $container->getLocator()->productAttribute()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSsoClient(Container $container): Container
    {
        $container->set(static::CLIENT_SSO, function (Container $container) {
            return $container->getLocator()->sso()->client();
        });

        return $container;
    }
}
