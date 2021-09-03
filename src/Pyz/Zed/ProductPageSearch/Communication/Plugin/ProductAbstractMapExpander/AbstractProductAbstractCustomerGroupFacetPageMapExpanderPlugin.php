<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
abstract class AbstractProductAbstractCustomerGroupFacetPageMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    protected const ATTRIBUTE_CUSTOMER_GROUP_PREFIX = 'customer_group_';
    protected const KEY_PRODUCT_LIST = 'product_list_map';
    protected const KEY_WHITE_LIST = 'whitelists';

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @param array $productLists
     *
     * @return string[]
     */
    abstract protected function collectCustomerGroups(array $productLists): array;

    /**
     * @param string $attributeKey
     *
     * @return string
     */
    protected function extractGroupNumber(string $attributeKey): string
    {
        return str_replace(self::ATTRIBUTE_CUSTOMER_GROUP_PREFIX, '', $attributeKey);
    }

    /**
     * @param array $productData
     *
     * @return array
     */
    protected function getProductLists(array $productData): array
    {
        return $productData[self::KEY_PRODUCT_LIST][self::KEY_WHITE_LIST] ?? [];
    }
}
