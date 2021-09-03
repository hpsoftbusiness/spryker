<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

class ProductAbstractCustomerGroupFacetPageMapExpanderPlugin extends AbstractProductAbstractCustomerGroupFacetPageMapExpanderPlugin
{
    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        $productLists = $this->getProductLists($productData);
        $customerGroups = $this->collectCustomerGroups($productLists);
        $pageMapBuilder->addStringFacet($pageMapTransfer, $this->getName(), $customerGroups);

        return $pageMapTransfer;
    }

    /**
     * @param array $productLists
     *
     * @return string[]
     */
    protected function collectCustomerGroups(array $productLists): array
    {
        $customerGroups = [];

        foreach ($productLists as $productList) {
            $productListTransfer = (new SpyProductListQuery())->findOneByIdProductList($productList);
            $customerGroups[] = $this->extractGroupNumber($productListTransfer->getKey());
        }

        return $customerGroups;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->getConfig()->getProductAbstractCustomerGroupFacetName();
    }
}
