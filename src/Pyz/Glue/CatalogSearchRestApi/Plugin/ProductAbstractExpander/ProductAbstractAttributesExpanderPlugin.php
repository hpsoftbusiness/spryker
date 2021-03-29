<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractAttributesExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTE_BRAND = 'brand';

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
        $this->setBrand($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setBrand(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $brandValue = $this->extractSingularAttributeValue($abstractProductData[self::ATTRIBUTE_BRAND] ?? null);
        if ($brandValue) {
            $restCatalogSearchAbstractProductsTransfer->setBrand((string)$brandValue);
        }
    }
}
