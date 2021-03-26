<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model;

use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Exception\TransactionRolledBackAwareExceptionInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAware;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataImporterDataSetWriterAwareConditional extends DataImporterDataSetWriterAware
{
    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected $dataSetCondition;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface $dataSetCondition
     *
     * @return void
     */
    public function setDataSetCondition(DataSetConditionInterface $dataSetCondition)
    {
        $this->dataSetCondition = $dataSetCondition;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function processDataSet(
        DataSetInterface $dataSet,
        DataImporterReportTransfer $dataImporterReportTransfer
    ): void {
        if ($this->dataSetCondition->hasData($dataSet)) {
            parent::processDataSet($dataSet, $dataImporterReportTransfer);
        } else {
            $dataImporterReportTransfer->setExpectedImportableDataSetCount(
                $dataImporterReportTransfer->getExpectedImportableDataSetCount() - 1
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function importByDataImporterConfiguration(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $source = $this->getSourceFromDataImporterConfigurationTransfer($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader, $source);

        $this->beforeImport();
        $imported = $dataImporterReportTransfer->getImportedDataSetCount();
        foreach ($dataReader as $dataSet) {
            try {
                $this->processDataSet($dataSet, $dataImporterReportTransfer);
            } catch (Exception $dataImportException) {
                $rolledCount = 1;
                $imported = $imported + $dataImporterReportTransfer->getImportedDataSetCount();

                if ($dataImportException instanceof TransactionRolledBackAwareExceptionInterface) {
                    if ($rolledCount > 1) {
                        $rolledCount = $dataImportException->getRolledBackRowsCount();
                    }
                    $dataImporterReportTransfer = $this->recalculateImportedDataSetCount(
                        $dataImporterReportTransfer,
                        $dataImportException
                    );
                }
                $countError = count($dataImporterReportTransfer->getMessages());
                $dataSetPosition = $dataImporterReportTransfer->getImportedDataSetCount(
                ) + $countError + $rolledCount + + $imported + 1;
                $exceptionMessage = $this->buildExceptionMessage(
                    $dataImportException,
                    $dataSetPosition
                );

                if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getThrowException()) {
                    throw new DataImportException($exceptionMessage, 0, $dataImportException);
                }

                ErrorLogger::getInstance()->log($dataImportException);

                $dataImporterReportMessageTransfer = new DataImporterReportMessageTransfer();
                $dataImporterReportMessageTransfer->setMessage($exceptionMessage);

                $dataImporterReportTransfer
                    ->setIsSuccess(false)
                    ->addMessage($dataImporterReportMessageTransfer);
            }

            unset($dataSet);
        }

        return $dataImporterReportTransfer;
    }
}
