<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantReferenceToIdMerchantStep as SprykerMerchantReferenceToIdMerchantStep;

class MerchantReferenceToIdMerchantStep extends SprykerMerchantReferenceToIdMerchantStep
{
    public const MERCHANT_REFERENCE = 'product.value_10';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[static::MERCHANT_REFERENCE]) || $dataSet[static::MERCHANT_REFERENCE] === "") {
            return;
        }

        parent::execute($dataSet);
    }
}
