<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\AffiliateDataStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\MerchantReferenceToIdMerchantStep;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferWriterStep as SprykerMerchantProductOfferWriterStep;

class MerchantProductOfferWriterStep extends SprykerMerchantProductOfferWriterStep
{
    public const BULK_SIZE = 1000;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferEntity = SpyProductOfferQuery::create()
            ->filterByProductOfferReference(
                sprintf(
                    '%s-%s',
                    $dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE],
                    $dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU]
                )
            )->findOneOrCreate();
        $productOfferEntity->setFkMerchant($dataSet[static::ID_MERCHANT]);
        $productOfferEntity->setConcreteSku($dataSet[static::CONCRETE_SKU]);
        $productOfferEntity->setMerchantSku($dataSet[static::MERCHANT_SKU] ?? null);
        $productOfferEntity->setIsActive(1);
        $productOfferEntity->setApprovalStatus(
            $dataSet[static::APPROVAL_STATUS] ?? ProductOfferConfig::STATUS_APPROVED
        );

        if ($dataSet[AffiliateDataStep::AFFILIATE_DATA_KEY]) {
            $productOfferEntity->setAffiliateData(json_encode($dataSet[AffiliateDataStep::AFFILIATE_DATA_KEY]));
        }

        $productOfferEntity->save();

        $this->addPublishEvent($productOfferEntity);
    }
}
