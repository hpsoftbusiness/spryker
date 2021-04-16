<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportFacade as SprykerDataImportFacade;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportBusinessFactory getFactory()
 */
class DataImportFacade extends SprykerDataImportFacade implements DataImportFacadeInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractPropelWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductAbstractDataImporter(): void
    {
        $this->getFactory()->createProductAbstractPropelWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcreteDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductConcretePropelWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductConcreteDataImporter(): void
    {
        $this->getFactory()->createProductConcretePropelWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImageDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductImagePropelWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductImageDataImporter(): void
    {
        $this->getFactory()->createProductImagePropelWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductStockDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductStockPropelWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductStockDataImporter(): void
    {
        $this->getFactory()->createProductStockPropelWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractStoreDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractStorePropelWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductAbstractStoreDataImporter(): void
    {
        $this->getFactory()->createProductAbstractStorePropelWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductPriceDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductPricePropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductPriceDataImporter(): void
    {
        $this->getFactory()->createCombinedProductPricePropelDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductImageDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductImagePropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductImageDataImporter(): void
    {
        $this->getFactory()->createCombinedProductImagePropelDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductStockDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductStockPropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductStockDataImporter(): void
    {
        $this->getFactory()->createCombinedProductStockPropelDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductAbstractStoreDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductAbstractStorePropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductAbstractStoreDataImporter(): void
    {
        $this->getFactory()->createCombinedProductAbstractStorePropelDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductAbstractDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductAbstractPropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductAbstractDataImporter(): void
    {
        $this->getFactory()->createCombinedProductAbstractPropelDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductConcreteDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductConcretePropelDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductConcreteDataImporter(): void
    {
        $this->getFactory()->createCombinedProductConcretePropelDataSetWriter()->flush();
    }

// => CTE

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductAbstractBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductAbstractBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductAbstractBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductAbstractBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductAbstractStoreBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductAbstractStoreBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductAbstractStoreBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductAbstractStoreBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductConcreteBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductConcreteBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductConcreteBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductConcreteBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductImageBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductImageBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductImageBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductImageBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductPriceBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductPriceBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductPriceBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductPriceBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductStockBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductStockBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductStockBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductStockBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductGroupBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductGroupBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductGroupBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductGroupBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeCombinedProductListProductConcreteBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createCombinedProductListProductConcreteBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushCombinedProductListProductConcreteBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createCombinedProductListProductConcreteBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeMerchantProductOfferBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createMerchantProductOfferBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushMerchantProductOfferBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createMerchantProductOfferBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeMerchantProductOfferStoreBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createMerchantProductOfferStoreBulkMariaDbPdoDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushMerchantProductOfferStoreBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createMerchantProductOfferStoreBulkMariaDbPdoDataSetWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writePriceProductOfferBulkMariaDbPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createPriceProductOfferBulkPdoMariaDbDataSetWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushPriceProductOfferBulkMariaDbPdoDataImporter(): void
    {
        $this->getFactory()->createPriceProductOfferBulkPdoMariaDbDataSetWriter()->flush();
    }
}
