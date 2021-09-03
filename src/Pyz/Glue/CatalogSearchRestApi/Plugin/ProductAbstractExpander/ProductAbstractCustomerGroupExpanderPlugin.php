<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractCustomerGroupExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    private const ATTRIBUTE_CUSTOMER_GROUP = 'customer_group';

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    public function expand(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $this->setCustomerGroup($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setCustomerGroup(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $customerGroup = $abstractProductData[self::ATTRIBUTE_CUSTOMER_GROUP] ?? [];

        $restCatalogSearchAbstractProductsTransfer->setCustomerGroup($customerGroup);
    }
}
