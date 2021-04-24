<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class DisableProductsWithoutOfferAfterImport implements DataImporterAfterImportInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected $dataSet;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

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
    public function afterImport()
    {
        $products = SpyProductQuery::create()
            ->leftJoinSpyProductAbstract()
            ->addJoin(
                [SpyProductTableMap::COL_SKU, SpyProductOfferTableMap::COL_IS_ACTIVE],
                [SpyProductOfferTableMap::COL_CONCRETE_SKU, true],
                Criteria::LEFT_JOIN
            )
            ->where(sprintf('%s = ?', SpyProductAbstractTableMap::COL_IS_AFFILIATE), true)
            ->where(sprintf('%s is null', SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER))
            ->find();

        foreach ($products as $product) {
            if ($product->isActive()) {
                $product->setIsActive(false);
                $product->save();
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $product->getIdProduct());
            }
        }
    }
}
