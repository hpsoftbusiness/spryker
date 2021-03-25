<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductAbstractAffiliateMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const KEY_IS_AFFILIATE = 'is_affiliate';
    private const KEY_AFFILIATE_DATA = 'affiliate_data';

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
        $isAffiliateValue = $this->getIsAffiliateFlagValue($productData);
        $pageMapTransfer->setIsAffiliate($isAffiliateValue);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_IS_AFFILIATE, $isAffiliateValue);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_AFFILIATE_DATA, $productData[self::KEY_AFFILIATE_DATA] ?? []);

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return bool
     */
    private function getIsAffiliateFlagValue(array $productData): bool
    {
        $isAffiliateValue = (bool)$productData[self::KEY_IS_AFFILIATE] ?? false;

        return $isAffiliateValue && $this->assertAffiliateDataHasValue($productData);
    }

    /**
     * @param array $productData
     *
     * @return bool
     */
    private function assertAffiliateDataHasValue(array $productData): bool
    {
        return !empty($productData[self::KEY_AFFILIATE_DATA]);
    }
}
