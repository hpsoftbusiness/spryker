<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductLabelWidget;

use Pyz\Client\ProductLabelStorage\ProductLabelStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToStoreClientInterface;
use SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetDependencyProvider;

class ProductLabelWidgetFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ProductLabelStorage\ProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient(): ProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelWidgetDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductLabelWidget\Dependency\Client\ProductLabelWidgetToStoreClientInterface
     */
    public function getStoreClient(): ProductLabelWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductLabelWidgetDependencyProvider::CLIENT_STORE);
    }
}
