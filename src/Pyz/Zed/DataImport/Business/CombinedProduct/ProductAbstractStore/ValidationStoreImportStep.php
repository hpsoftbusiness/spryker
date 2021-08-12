<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore;

use Pyz\Zed\DataImport\Business\Exception\InvalidStoreException;
use Pyz\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ValidationStoreImportStep implements DataImportStepInterface
{
    private const APPLICATION_STORE = 'APPLICATION_STORE';
    /**
     * @var \Pyz\Zed\DataImport\DataImportConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\DataImport\DataImportConfig $config
     */
    public function __construct(DataImportConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->validateStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidStoreException
     *
     * @return void
     */
    private function validateStore(DataSetInterface $dataSet): void
    {
        if ($this->config->getNeedStoreRelationValidation()) {
            $expectedStore = $_SERVER[self::APPLICATION_STORE];
            $dataSetStore = $dataSet[CombinedProductAbstractStoreHydratorStep::COLUMN_STORE_NAME];

            if ($dataSetStore !== $expectedStore) {
                throw new InvalidStoreException($expectedStore, $dataSetStore);
            }
        }
    }
}
