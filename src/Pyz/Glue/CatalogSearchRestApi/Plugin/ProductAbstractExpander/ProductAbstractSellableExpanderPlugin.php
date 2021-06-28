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

class ProductAbstractSellableExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTE_SELLABLE_PREFIX = 'sellable_';
    private const ATTRIBUTES_KEY = 'attributes';

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
        $restCatalogSearchAbstractProductsTransfer->setSellable($this->collectSellableAttributes($abstractProductData));
    }

    /**
     * @param array $abstractProductData
     *
     * @return bool[]
     */
    private function collectSellableAttributes(array $abstractProductData): array
    {
        $sellableAttributes = [];
        $attributes = $abstractProductData[self::ATTRIBUTES_KEY];

        foreach ($attributes as $key => $value) {
            if (strpos($key, self::ATTRIBUTE_SELLABLE_PREFIX) !== false) {
                $sellableAttributes[$key] = (bool)$this->extractSingularAttributeValue($value);
            }
        }

        return $sellableAttributes;
    }
}
