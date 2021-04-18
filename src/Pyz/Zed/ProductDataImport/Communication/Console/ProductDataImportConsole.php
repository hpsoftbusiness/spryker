<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Console;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 */
class ProductDataImportConsole extends Console
{
    private const  DATA_ENTITY_ABSTRACT = 'combined-product-abstract';
    private const  DATA_ENTITY_ABSTRACT_STORE = 'combined-product-abstract-store';
    private const  DATA_ENTITY_CONCRETE = 'combined-product-concrete';
    private const  DATA_ENTITY_IMAGE = 'combined-product-image';
    private const  DATA_ENTITY_PRICE = 'combined-product-price';
    private const  DATA_ENTITY_STOCK = 'combined-product-stock';
    private const  DATA_ENTITY_LIST_CONCRETE = 'combined-product-list-product-concrete';
    private const  DATA_ENTITY_MERCHANT_PRODUCT_OFFER = 'merchant-product-offer';
    private const  DATA_ENTITY_MERCHANT_PRODUCT_OFFER_STORE = 'merchant-product-offer-store';
    private const  DATA_ENTITY_PRICE_PRODUCT_OFFER = 'price-product-offer';

    protected const DATA_ENTITY_FOR_PRODUCT = [
        self::DATA_ENTITY_ABSTRACT,
        self::DATA_ENTITY_ABSTRACT_STORE,
        self::DATA_ENTITY_CONCRETE,
        self::DATA_ENTITY_LIST_CONCRETE,
        self::DATA_ENTITY_IMAGE,
        self::DATA_ENTITY_PRICE,
        self::DATA_ENTITY_STOCK,
        self::DATA_ENTITY_MERCHANT_PRODUCT_OFFER,
        self::DATA_ENTITY_MERCHANT_PRODUCT_OFFER_STORE,
        self::DATA_ENTITY_PRICE_PRODUCT_OFFER,
    ];

    public const COMMAND_NAME = 'data:product:import-file';
    public const DESCRIPTION = 'Import products from file that was uploaded from Zed, data in table: spy_product_data_import';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $progressBar = new ProgressBar($output, count(self::DATA_ENTITY_FOR_PRODUCT) + 2);
        $progressBar->setFormat('debug');
        $progressBar->start();

        $messenger = $this->getMessenger();
        $result = null;
        $productDataImport = $this->getFacade()->getProductDataImportForImport();

        $storageClient = $this->getFactory()->getStorageClient();

        $storageClient->set('IS_DATA_IMPORT_IN_PROGRESS', true);
        apcu_add('IS_DATA_IMPORT_IN_PROGRESS', true);

        if ($productDataImport) {
            Propel::disableInstancePooling();

            $output->writeln(' <fg=yellow> Prepare import</>');
            $this->getFacade()->prepareImportFile($productDataImport);

            $progressBar->advance();

            try {
                foreach (self::DATA_ENTITY_FOR_PRODUCT as $dataEntity) {
                    $output->writeln(sprintf(' <fg=yellow> Start import: %s</>', $dataEntity));
                    $this->getFacade()->import($productDataImport, $dataEntity);
                    $progressBar->advance();
                }
            } catch (Exception $e) {
                $storageClient->delete('IS_DATA_IMPORT_IN_PROGRESS');
                apcu_delete('IS_DATA_IMPORT_IN_PROGRESS');
            }

            $output->writeln(' <fg=yellow> Clear file</>');

            $this->getFacade()->clearImportFile();
            $progressBar->advance();

            Propel::enableInstancePooling();
        }
        $progressBar->finish();

        $storageClient->delete('IS_DATA_IMPORT_IN_PROGRESS');
        apcu_delete('IS_DATA_IMPORT_IN_PROGRESS');

        $output->writeln(' <fg=green> Finish</>');
        $messenger->info(
            sprintf(
                'You just executed %s!',
                static::COMMAND_NAME,
            )
        );

        return static::CODE_SUCCESS;
    }
}
