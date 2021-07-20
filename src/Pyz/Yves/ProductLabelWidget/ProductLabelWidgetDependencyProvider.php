<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductLabelWidget;

use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetDependencyProvider as SprykerProductLabelWidgetDependencyProvider;

class ProductLabelWidgetDependencyProvider extends SprykerProductLabelWidgetDependencyProvider
{
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductLabelStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_LABEL_STORAGE, function (Container $container) {
            return $container->getLocator()->productLabelStorage()->client();
        });

        return $container;
    }
}
