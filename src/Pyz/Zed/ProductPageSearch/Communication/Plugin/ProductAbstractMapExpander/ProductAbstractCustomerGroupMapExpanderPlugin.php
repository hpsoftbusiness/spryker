<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Pyz\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductAbstractCustomerGroupMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    protected const KEY_CUSTOMER_GROUP = 'customer_group';
    protected const KEY_PRODUCT_LIST = 'product_list_map';
    protected const KEY_WHITE_LIST = 'whitelists';

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
        $productList = $this->getProductLists($productData);
        $customerGroup = $this->collectCustomerGroups($productList);

        $pageMapTransfer->setCustomerGroup($customerGroup);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_CUSTOMER_GROUP, $customerGroup);

        return $pageMapTransfer;
    }

    /**
     * @param array $productLists
     *
     * @return array
     */
    protected function collectCustomerGroups(array $productLists): array
    {
        $customerGroups = [];
        $productListsTransfer = (new SpyProductListQuery())->find();

        foreach ($productListsTransfer as $productListTransfer) {
            $customerGroups[$productListTransfer->getKey()] = in_array(
                $productListTransfer->getIdProductList(),
                $productLists
            );
        }

        return $customerGroups;
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
