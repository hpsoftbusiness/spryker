<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Communication\Plugin\Product;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generator;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductDefaultTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Psr\Log\LoggerInterface;
use Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface getFacade()
 * @method \Pyz\Zed\PriceProduct\Communication\PriceProductCommunicationFactory getFactory()
 * @method \Pyz\Zed\PriceProduct\PriceProductConfig getConfig()
 */
class BenefitPriceDataHealerPlugin extends AbstractPlugin implements ProductDataHealerPluginInterface
{
    use TransactionTrait;

    private const RESOURCE_NAME = 'BENEFIT_PRICE_HEALER';

    private const CHUNK_SIZE = 100;

    private const KEY_ABSTRACT_SKU = 'abstract_sku';
    private const KEY_ID_PRICE_PRODUCT = 'id_price_product';
    private const KEY_ID_PRICE_PRODUCT_DEFAULT = 'id_price_product_default';

    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    private $converter;

    /**
     * @var \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    private $logger;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::RESOURCE_NAME;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function execute(?LoggerInterface $logger = null): void
    {
        $this->logger = $logger;
        $this->setConverter();

        $benefitTypeTransfer = $this->getBenefitPriceType();
        $currencyTransfer = $this->getFactory()->getCurrencyFacade()->getDefaultCurrencyForCurrentStore();
        $storeTransfer = $this->getFactory()->getStoreFacade()->getCurrentStore();
        $priceDimensionTransfer = $this->createDimensionTransfer();

        $this->logger->notice('Setting BENEFIT price for concrete products...');
        $this->setProductConcreteBenefitPrices($benefitTypeTransfer, $currencyTransfer, $storeTransfer, $priceDimensionTransfer);
        $this->logger->notice('Setting BENEFIT price for abstract products...');
        $this->setProductAbstractBenefitPrices($benefitTypeTransfer, $currencyTransfer, $storeTransfer, $priceDimensionTransfer);

        $this->logger->notice('Finished');
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $benefitTypeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer
     *
     * @return void
     */
    private function setProductConcreteBenefitPrices(
        PriceTypeTransfer $benefitTypeTransfer,
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer,
        PriceProductDimensionTransfer $priceDimensionTransfer
    ): void {
        $lastProcessedId = 0;
        foreach ($this->getConcreteProducts($benefitTypeTransfer->getIdPriceType(), $lastProcessedId) as $productCollection) {
            foreach ($productCollection as $spyProduct) {
                $lastProcessedId = $spyProduct->getIdProduct();
                $benefitPriceAmount = $this->getConvertedBenefitPriceAmount($spyProduct->getAttributes());
                if ($benefitPriceAmount === null) {
                    $this->logger->info('Skipping product ID ' . $spyProduct->getIdProduct() . '.');
                    continue;
                }

                $priceProductTransfer = $this->createConcretePriceProductTransfer($spyProduct, $benefitTypeTransfer);
                $moneyValueTransfer = $this->createMoneyValueTransfer($currencyTransfer, $storeTransfer, $benefitPriceAmount);
                $priceProductTransfer->setMoneyValue($moneyValueTransfer);
                $priceDimensionTransfer->setIdPriceProductDefault($spyProduct->getVirtualColumn(self::KEY_ID_PRICE_PRODUCT_DEFAULT));
                $priceProductTransfer->setPriceDimension($priceDimensionTransfer);

                $this->savePriceProduct($priceProductTransfer);
                $this->logger->info('BENEFIT price created for product ID ' . $spyProduct->getIdProduct() . '.');
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $benefitTypeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer
     *
     * @return void
     */
    private function setProductAbstractBenefitPrices(
        PriceTypeTransfer $benefitTypeTransfer,
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer,
        PriceProductDimensionTransfer $priceDimensionTransfer
    ): void {
         $lastProcessedId = 0;
        foreach ($this->getAbstractProducts($benefitTypeTransfer->getIdPriceType(), $lastProcessedId) as $abstractProductCollection) {
            foreach ($abstractProductCollection as $spyProductAbstract) {
                $lastProcessedId = $spyProductAbstract->getIdProductAbstract();
                $benefitPriceAmount = $this->getConvertedBenefitPriceAmount($spyProductAbstract->getAttributes());
                if ($benefitPriceAmount === null) {
                    $this->logger->info('Skipping abstract product ID ' . $spyProductAbstract->getIdProductAbstract() . '.');
                    continue;
                }

                $priceProductTransfer = $this->createAbstractPriceProductTransfer($spyProductAbstract, $benefitTypeTransfer);
                $moneyValueTransfer = $this->createMoneyValueTransfer($currencyTransfer, $storeTransfer, $benefitPriceAmount);
                $priceProductTransfer->setMoneyValue($moneyValueTransfer);
                $priceDimensionTransfer->setIdPriceProductDefault($spyProductAbstract->getVirtualColumn(self::KEY_ID_PRICE_PRODUCT_DEFAULT));
                $priceProductTransfer->setPriceDimension($priceDimensionTransfer);

                $this->savePriceProduct($priceProductTransfer);
                $this->logger->info('BENEFIT price created for abstract product ID ' . $spyProductAbstract->getIdProductAbstract() . '.');
            }
        }
    }

    /**
     * @param int $idPriceType
     * @param int $lastProcessedId
     *
     * @return \Generator
     */
    private function getConcreteProducts(int $idPriceType, int &$lastProcessedId): Generator
    {
        do {
            $spyProductEntities = SpyProductQuery::create()
                ->withColumn(SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT, self::KEY_ID_PRICE_PRODUCT)
                ->withColumn(SpyProductAbstractTableMap::COL_SKU, self::KEY_ABSTRACT_SKU)
                ->withColumn(SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT, self::KEY_ID_PRICE_PRODUCT_DEFAULT)
                ->filterByIdProduct($lastProcessedId, Criteria::GREATER_THAN)
                ->joinSpyProductAbstract()
                ->usePriceProductQuery()
                ->usePriceProductStoreQuery(null, Criteria::LEFT_JOIN)
                ->joinPriceProductDefault(null, Criteria::LEFT_JOIN)
                ->endUse()
                ->endUse()
                ->addJoinCondition('PriceProduct', 'PriceProduct.fk_price_type = ?', $idPriceType)
                ->addCond('priceProductExists', SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT)
                ->addCond('priceProductStoreExists', SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE)
                ->combine(['priceProductExists', 'priceProductStoreExists'], Criteria::LOGICAL_OR, 'priceExists')
                ->addCond('grossPriceNull', SpyPriceProductStoreTableMap::COL_GROSS_PRICE)
                ->addCond('defaultPriceFlagged', SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT, null, Criteria::ISNOTNULL)
                ->combine(['grossPriceNull', 'defaultPriceFlagged'], Criteria::LOGICAL_AND, 'priceSet')
                ->where(['priceExists', 'priceSet'], Criteria::LOGICAL_OR)
                ->groupByIdProduct()
                ->orderByIdProduct()
                ->limit(self::CHUNK_SIZE)
                ->find();

                yield $spyProductEntities->getData();
        } while ($spyProductEntities->count() > 0);
    }

    /**
     * @param int $idPriceType
     * @param int $lastProcessedId
     *
     * @return \Generator
     */
    private function getAbstractProducts(int $idPriceType, int &$lastProcessedId): Generator
    {
        do {
            $spyProductEntities = SpyProductAbstractQuery::create()
                ->withColumn(SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT, self::KEY_ID_PRICE_PRODUCT)
                ->withColumn(SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT, self::KEY_ID_PRICE_PRODUCT_DEFAULT)
                ->filterByIdProductAbstract($lastProcessedId, Criteria::GREATER_THAN)
                ->usePriceProductQuery()
                ->usePriceProductStoreQuery(null, Criteria::LEFT_JOIN)
                ->joinPriceProductDefault(null, Criteria::LEFT_JOIN)
                ->endUse()
                ->endUse()
                ->addJoinCondition('PriceProduct', 'PriceProduct.fk_price_type = ?', $idPriceType)
                ->addCond('priceProductExists', SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT)
                ->addCond('priceProductStoreExists', SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE)
                ->combine(['priceProductExists', 'priceProductStoreExists'], Criteria::LOGICAL_OR, 'priceExists')
                ->addCond('grossPriceNull', SpyPriceProductStoreTableMap::COL_GROSS_PRICE)
                ->addCond('defaultPriceFlagged', SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT, null, Criteria::ISNOTNULL)
                ->combine(['grossPriceNull', 'defaultPriceFlagged'], Criteria::LOGICAL_AND, 'priceSet')
                ->where(['priceExists', 'priceSet'], Criteria::LOGICAL_OR)
                ->groupByIdProductAbstract()
                ->orderByIdProductAbstract()
                ->limit(self::CHUNK_SIZE)
                ->find();

            yield $spyProductEntities->getData();
        } while ($spyProductEntities->count() > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    private function savePriceProduct(PriceProductTransfer $priceProductTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($priceProductTransfer) {
            if ($priceProductTransfer->getIdPriceProduct()) {
                $this->getFacade()->setPriceForProduct($priceProductTransfer);
            } else {
                $this->getFacade()->createPriceForProduct($priceProductTransfer);
            }
        });
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProduct
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $benefitPriceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createConcretePriceProductTransfer(
        SpyProduct $spyProduct,
        PriceTypeTransfer $benefitPriceType
    ): PriceProductTransfer {
        $priceProductTransfer = new PriceProductTransfer();
        $priceProductTransfer->setIdProduct($spyProduct->getIdProduct());
        $priceProductTransfer->setSkuProduct($spyProduct->getSku());
        $priceProductTransfer->setSkuProductAbstract($spyProduct->getVirtualColumn(self::KEY_ABSTRACT_SKU));
        $priceProductTransfer->setFkPriceType($benefitPriceType->getIdPriceType());
        $priceProductTransfer->setPriceType($benefitPriceType);
        $priceProductTransfer->setPriceTypeName($benefitPriceType->getName());
        $priceProductTransfer->setIdPriceProduct($spyProduct->getVirtualColumn(self::KEY_ID_PRICE_PRODUCT));

        return $priceProductTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstract
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $benefitPriceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createAbstractPriceProductTransfer(
        SpyProductAbstract $spyProductAbstract,
        PriceTypeTransfer $benefitPriceType
    ): PriceProductTransfer {
        $priceProductTransfer = new PriceProductTransfer();
        $priceProductTransfer->setIdProductAbstract($spyProductAbstract->getIdProductAbstract());
        $priceProductTransfer->setSkuProductAbstract($spyProductAbstract->getSku());
        $priceProductTransfer->setSkuProduct('placeholder');
        $priceProductTransfer->setFkPriceType($benefitPriceType->getIdPriceType());
        $priceProductTransfer->setPriceType($benefitPriceType);
        $priceProductTransfer->setPriceTypeName($benefitPriceType->getName());
        $priceProductTransfer->setIdPriceProduct($spyProductAbstract->getVirtualColumn(self::KEY_ID_PRICE_PRODUCT));

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    private function createMoneyValueTransfer(
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer,
        int $amount
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setGrossAmount($amount)
            ->setNetAmount($amount)
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    private function createDimensionTransfer(): PriceProductDimensionTransfer
    {
        return (new PriceProductDimensionTransfer())
            ->setType($this->getFactory()->getConfig()->getPriceDimensionDefault())
            ->setName($this->getFactory()->getConfig()->getPriceDimensionDefaultName());
    }

    /**
     * @param string $jsonAttributes
     *
     * @return int|null
     */
    private function getConvertedBenefitPriceAmount(string $jsonAttributes): ?int
    {
        $attributes = json_decode($jsonAttributes, true);
        $benefitPrice = $attributes[$this->getConfig()->getProductAttributeKeyBenefitSalesPrice()] ?? null;
        if ($benefitPrice !== null) {
            $benefitPrice = $this->converter->convert((float)$benefitPrice);
        }

        return $benefitPrice;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    private function getBenefitPriceType(): PriceTypeTransfer
    {
        $benefitTypeName = $this->getFacade()->getBenefitPriceTypeName();
        $priceType = $this->getFacade()->findPriceTypeByName($benefitTypeName);
        if (!$priceType) {
            $this->logger->alert('BENEFIT price type missing, installing price types...');
            $this->getFacade()->install();

            $priceType = $this->getFacade()->findPriceTypeByName($benefitTypeName);
        }

        return $priceType;
    }

    /**
     * @return void
     */
    private function setConverter(): void
    {
        $this->converter = $this->getFactory()->createDecimalToIntegerConverter();
    }
}
