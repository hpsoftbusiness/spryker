<?php

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
class ProductAbstractEliteClubDealsMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const KEY_EC_DEALS = 'ec_deals';
    private const KEY_ONLY_EC_DEALS = 'only_ec_deals';
    private const KEY_PRODUCT_LIST = 'product_list_map';
    private const KEY_WHITE_LIST = 'whitelists';
    private const KEY_EC_DEALS_GROUP = 'customer_group_6';

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer|void
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ) {
        $ecDeals = $this->getEcDeals($productData);
        $onlyEcDeal = (bool)($ecDeals && (count($productData[self::KEY_PRODUCT_LIST][self::KEY_WHITE_LIST]) === 1));
        $pageMapTransfer->setEcDeals($ecDeals);
        $pageMapTransfer->setOnlyEcDeals($onlyEcDeal);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_EC_DEALS, $ecDeals);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_ONLY_EC_DEALS, $onlyEcDeal);

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return bool
     */
    private function getEcDeals(array $productData): bool
    {
        $eliteClubProductList = (new SpyProductListQuery())->findOneByKey(self::KEY_EC_DEALS_GROUP);
        if ($eliteClubProductList === null) {
            return false;
        }

        return in_array(
            $eliteClubProductList->getIdProductList(),
            $productData[self::KEY_PRODUCT_LIST][self::KEY_WHITE_LIST] ?? []
        );
    }
}
