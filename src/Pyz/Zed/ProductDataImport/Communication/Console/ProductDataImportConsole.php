<?php

namespace Pyz\Zed\ProductDataImport\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 */
class ProductDataImportConsole extends Console
{
    private const  DATA_ENTITY = 'combined-product-abstract';
    const COMMAND_NAME = 'data:product:import-file';
    const DESCRIPTION = 'Import products from file that was uploaded from Zed, data in table: spy_product_data_import';

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
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $messenger = $this->getMessenger();

        $result = null;
        $productDataImport = $this->getFacade()->getProductDataImportForImport();

        if ($productDataImport) {
            $result = $this->getFacade()->import($productDataImport, static::DATA_ENTITY);
        }
        $messenger->info(
            sprintf(
                'You just executed %s! Result: %s',
                static::COMMAND_NAME,
                $result
            )
        );

        return static::CODE_SUCCESS;
    }
}
