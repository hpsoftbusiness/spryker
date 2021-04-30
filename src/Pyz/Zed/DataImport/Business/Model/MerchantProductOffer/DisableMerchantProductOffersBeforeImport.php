<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;

class DisableMerchantProductOffersBeforeImport implements DataImporterBeforeImportInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    private $dataSet;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    private $eventFacade;

    /**
     * @var \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected $entityEventTransfers = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(DataSetInterface $dataSet, DataImportToEventFacadeInterface $eventFacade)
    {
        $this->dataSet = $dataSet;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @return void
     */
    public function beforeImport(): void
    {
        if (!isset($this->dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE]) ||
            $this->dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE] === ""
        ) {
            return;
        }

        $merchant = SpyMerchantQuery::create()->findOneByMerchantReference($this->getMerchantReference());
        $spyProductOffers = SpyProductOfferQuery::create()->findByFkMerchant($merchant->getIdMerchant());

        foreach ($spyProductOffers as $productOffer) {
            $productOffer->setIsActive(0);
            $productOffer->save();
            $this->addPublishEvent($productOffer);
        }
        $this->triggerEvents();
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     *
     * @return void
     */
    protected function addPublishEvent(SpyProductOffer $productOfferEntity): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($productOfferEntity->getIdProductOffer());
        $eventEntityTransfer->setAdditionalValues(
            [
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferEntity->getProductOfferReference(),
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferEntity->getConcreteSku(),
            ]
        );

        $this->entityEventTransfers[] = $eventEntityTransfer;
    }

    /**
     * @return void
     */
    private function triggerEvents(): void
    {
        $this->eventFacade->triggerBulk(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH, $this->entityEventTransfers);
        $this->entityEventTransfers = [];
    }

    /**
     * @return string
     */
    private function getMerchantReference(): string
    {
        return $this->dataSet[MerchantReferenceToIdMerchantStep::MERCHANT_REFERENCE];
    }
}
