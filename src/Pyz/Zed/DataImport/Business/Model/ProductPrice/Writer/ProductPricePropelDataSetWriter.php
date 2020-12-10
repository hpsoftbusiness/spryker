<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductPricePropelDataSetWriter implements DataSetWriterInterface
{
    protected const COLUMN_ABSTRACT_SKU = ProductPriceHydratorStep::COLUMN_ABSTRACT_SKU;
    protected const COLUMN_CONCRETE_SKU = ProductPriceHydratorStep::COLUMN_CONCRETE_SKU;
    protected const COLUMN_STORE = ProductPriceHydratorStep::COLUMN_STORE;
    protected const COLUMN_CURRENCY = ProductPriceHydratorStep::COLUMN_CURRENCY;
    protected const COLUMN_PRICE_GROSS = CombinedProductPriceHydratorStep::COLUMN_PRICE_GROSS;
    protected const COLUMN_PRICE_GROSS_ORIGINAL = CombinedProductPriceHydratorStep::COLUMN_PRICE_GROSS_ORIGINAL;
    protected const COLUMN_PRICE_NET = ProductPriceHydratorStep::COLUMN_PRICE_NET;
    protected const COLUMN_PRICE_DATA = ProductPriceHydratorStep::COLUMN_PRICE_DATA;
    protected const COLUMN_PRICE_DATA_CHECKSUM = ProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM;
    protected const COLUMN_PRICE_TYPE_DEFAULT = CombinedProductPriceHydratorStep::DEFAULT_PRICE_TYPE;
    protected const COLUMN_PRICE_TYPE_ORIGINAL = CombinedProductPriceHydratorStep::ORIGINAL_PRICE_TYPE;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        ProductRepository $productRepository,
        StoreFacadeInterface $storeFacade,
        CurrencyFacadeInterface $currencyFacade
    )
    {
        $this->productRepository = $productRepository;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $priceTypeTransfers = $dataSet[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            if ($this->isPriceTypeRequired($dataSet, $priceTypeTransfer->getName())) {
                $priceTypeEntity = $this->findOrCreatePriceType($dataSet, $priceTypeTransfer);
                $productPriceEntity = $this->findOrCreateProductPrice($dataSet, $priceTypeEntity);
                $priceProductStoreEntity = $this->findOrCreatePriceProductStore($dataSet, $productPriceEntity);
                $this->findOrCreatePriceProductDefault($priceProductStoreEntity);
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $priceTypeName
     *
     * @return bool
     */
    protected function isPriceTypeRequired(DataSetInterface $dataSet, string $priceTypeName): bool
    {
        return $priceTypeName === static::COLUMN_PRICE_TYPE_DEFAULT ||
            ($priceTypeName === static::COLUMN_PRICE_TYPE_ORIGINAL &&
            $dataSet[static::COLUMN_PRICE_GROSS_ORIGINAL] > $dataSet[static::COLUMN_PRICE_GROSS]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\SpyPriceTypeEntityTransfer|null $priceTypeTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    protected function findOrCreatePriceType(DataSetInterface $dataSet, SpyPriceTypeEntityTransfer $priceTypeTransfer = null): SpyPriceType
    {
        $dataSet[ProductPriceHydratorStep::COLUMN_PRICE_TYPE] = $priceTypeTransfer->getName();

        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName($priceTypeTransfer->getName())
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        return $priceTypeEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     */
    protected function findOrCreateProductPrice(
        DataSetInterface $dataSet,
        SpyPriceType $priceTypeEntity
    ): SpyPriceProduct
    {
        $query = SpyPriceProductQuery::create();
        $query->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        if (empty($dataSet[static::COLUMN_ABSTRACT_SKU]) && empty($dataSet[static::COLUMN_CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                static::COLUMN_ABSTRACT_SKU,
                static::COLUMN_CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[static::COLUMN_ABSTRACT_SKU])) {
            $idProductAbstract = $this->productRepository->getIdProductAbstractByAbstractSku($dataSet[static::COLUMN_ABSTRACT_SKU]);
            $query->filterByFkProductAbstract($idProductAbstract);
            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $idProductAbstract);
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
        } else {
            $idProduct = $this->productRepository->getIdProductByConcreteSku($dataSet[static::COLUMN_CONCRETE_SKU]);
            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $idProduct);
            $query->filterByFkProduct($idProduct);
        }

        $productPriceEntity = $query->findOneOrCreate();
        $productPriceEntity->save();

        return $productPriceEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet $dataSet
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $spyPriceProduct
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function findOrCreatePriceProductStore(
        DataSetInterface $dataSet,
        SpyPriceProduct $spyPriceProduct
    ): SpyPriceProductStore
    {
        $storeTransfer = $this->storeFacade->getStoreByName($dataSet[static::COLUMN_STORE]);
        $currencyTransfer = $this->currencyFacade->fromIsoCode($dataSet[static::COLUMN_CURRENCY]);

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkPriceProduct($spyPriceProduct->getPrimaryKey())
            ->findOneOrCreate();

        $defaultGrossPriceKey = static::COLUMN_PRICE_GROSS;
        if ($dataSet[ProductPriceHydratorStep::COLUMN_PRICE_TYPE] === CombinedProductPriceHydratorStep::ORIGINAL_PRICE_TYPE) {
            $defaultGrossPriceKey = static::COLUMN_PRICE_GROSS_ORIGINAL;
        }

        $netPrice = (float)str_replace(',', '.', $dataSet[static::COLUMN_PRICE_NET]) * 100;
        $grossPrice = (float)str_replace(',', '.', $dataSet[$defaultGrossPriceKey]) * 100;

        if ($dataSet[CombinedProductPriceHydratorStep::COLUMN_IS_AFFILIATE_PRODUCT]) {
            $grossPrice = $netPrice = (float)str_replace(',', '.', $dataSet[CombinedProductPriceHydratorStep::COLUMN_AFFILIATE_PRODUCT_PRICE]) * 100;
        }

        $priceProductStoreEntity->setGrossPrice($grossPrice);
        $priceProductStoreEntity->setNetPrice($netPrice);

        $priceProductStoreEntity->setPriceData($dataSet[static::COLUMN_PRICE_DATA]);
        $priceProductStoreEntity->setPriceDataChecksum($dataSet[static::COLUMN_PRICE_DATA_CHECKSUM]);

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault
     */
    protected function findOrCreatePriceProductDefault(SpyPriceProductStore $priceProductStore): SpyPriceProductDefault
    {
        $priceProductDefault = SpyPriceProductDefaultQuery::create()
            ->filterByFkPriceProductStore($priceProductStore->getIdPriceProductStore())
            ->findOneOrCreate();

        $priceProductDefault->save();

        return $priceProductDefault;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        DataImporterPublisher::triggerEvents();
    }
}
