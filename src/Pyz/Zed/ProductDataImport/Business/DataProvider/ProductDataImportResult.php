<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportResultTransfer;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImportInterface;

class ProductDataImportResult
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDataImportResultTransfer
     */
    public function getDataImporterReportResultTransfers(
        DataImporterReportTransfer $dataImporterReportTransfer
    ): ProductDataImportResultTransfer {
        $productDataImportResultTransfer = new ProductDataImportResultTransfer();

        $productDataImportResultTransfer->setStatus(
            $dataImporterReportTransfer->getIsSuccess(
            ) ? ProductDataImportInterface::STATUS_SUCCESS : ProductDataImportInterface::STATUS_FAILED
        );

        foreach ($dataImporterReportTransfer->getDataImporterReports() as $reportTransfer) {
            $productDataImportResultTransfer->setType($reportTransfer->getImportType());
            $productDataImportResultTransfer->setImportedCount($reportTransfer->getImportedDataSetCount());
            $productDataImportResultTransfer->setFailed(
                $reportTransfer->getExpectedImportableDataSetCount() - $reportTransfer->getImportedDataSetCount()
            );

            $productDataImportResultTransfer->setMessages($this->getMessages($reportTransfer->getMessages()));
        }

        return $productDataImportResultTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DataImporterReportMessageTransfer[] $transferMessages
     *
     * @return array
     */
    private function getMessages(ArrayObject $transferMessages): array
    {
        $messages = [];

        foreach ($transferMessages as $message) {
            // in message we have string that contain exception trace that we dont need
            $strMessage = strstr($message->getMessage(), "For debugging execute", true);
            $messages[] = ($strMessage) ? $strMessage : $message->getMessage();
            unset($strMessage);
        }

        return $messages;
    }
}
