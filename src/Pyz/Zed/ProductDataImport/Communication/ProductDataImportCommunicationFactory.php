<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Pyz\Zed\ProductDataImport\Communication\Form\ProductDataImportForm;
use Pyz\Zed\ProductDataImport\Communication\Table\ProductDataImportTable;
use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacadeInterface getFacade()
 */
class ProductDataImportCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ProductDataImport\Communication\Table\ProductDataImportTable
     */
    public function createProductImportTable(): ProductDataImportTable
    {
        return new ProductDataImportTable($this->getQueryContainer());
    }

    /**
     * @return \Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider
     */
    public function createProductDataImportFormDataProvider(): ProductDataImportFormDataProvider
    {
        return new ProductDataImportFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductDataImportForm(
        ProductDataImportTransfer $data,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(ProductDataImportForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::CLIENT_STORAGE);
    }
}
