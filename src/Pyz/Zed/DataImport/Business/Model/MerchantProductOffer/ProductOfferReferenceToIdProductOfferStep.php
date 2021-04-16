<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ProductOfferReferenceToIdProductOfferStep as SprykerProductOfferReferenceToIdProductOfferStep;

class ProductOfferReferenceToIdProductOfferStep extends SprykerProductOfferReferenceToIdProductOfferStep
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReference = sprintf(
            '%s-%s',
            $dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE],
            $dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU]
        );

        if (!$productOfferReference) {
            throw new InvalidDataException(
                sprintf('"%s" is required.', MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE)
            );
        }

        if (!isset($this->idProductOfferCache[$productOfferReference])) {
            $this->idProductOfferCache[$productOfferReference] = $this->getIdProductOffer($productOfferReference);
        }

        $dataSet[MerchantProductOfferDataSetInterface::ID_PRODUCT_OFFER] = $this->idProductOfferCache[$productOfferReference];
    }
}
