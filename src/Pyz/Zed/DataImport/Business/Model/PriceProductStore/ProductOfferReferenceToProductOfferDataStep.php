<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\ProductOfferReferenceToProductOfferDataStep as SprykerProductOfferReferenceToProductOfferDataStep;

class ProductOfferReferenceToProductOfferDataStep extends SprykerProductOfferReferenceToProductOfferDataStep
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReferenceKey = sprintf(
            '%s-%s',
            $dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE],
            $dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU]
        );

        if (!isset($this->productOfferDataCache[$productOfferReferenceKey])) {
            $productOfferQuery = SpyProductOfferQuery::create();
            $productOfferQuery->select(
                [SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, SpyProductOfferTableMap::COL_CONCRETE_SKU]
            );

            $productOfferEntity = $productOfferQuery->findOneByProductOfferReference($productOfferReferenceKey);
            if (!$productOfferEntity) {
                throw new EntityNotFoundException(
                    sprintf('Could not find product offer by product offer reference "%s"', $productOfferReferenceKey)
                );
            }

            $this->productOfferDataCache[$productOfferReferenceKey] = $productOfferEntity;
        }

        $dataSet[static::FK_PRODUCT_OFFER] = $this->productOfferDataCache[$productOfferReferenceKey][SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER];
        $dataSet[static::CONCRETE_SKU] = $this->productOfferDataCache[$productOfferReferenceKey][SpyProductOfferTableMap::COL_CONCRETE_SKU];
    }
}
