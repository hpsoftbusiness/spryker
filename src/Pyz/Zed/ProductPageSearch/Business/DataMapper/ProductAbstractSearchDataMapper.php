<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\DataMapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\ProductAbstractSearchDataMapper as SprykerProductAbstractSearchDataMapper;

class ProductAbstractSearchDataMapper extends SprykerProductAbstractSearchDataMapper
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function buildPageMap(array $data, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        $pageMapTransfer = parent::buildPageMap($data, $localeTransfer);

        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'is_affiliate', $data['is_affiliate'] ?? false);
        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'affiliate_data', $data['affiliate_data'] ?? []);
        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'brand', $data['attributes']['brand'] ?? '');
        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'cashback_amount', $data['attributes']['cashback_amount'] ?? '');
        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'shopping_points', $data['attributes']['shopping_points'] ?? '');

        return $pageMapTransfer;
    }
}
