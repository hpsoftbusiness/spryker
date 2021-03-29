<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\DataProvider;

use ArrayObject;

class ProductDataImportResult
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer[] $transferReports
     *
     * @return array
     */
    public function collectionDataImporterReportTransferToString(array $transferReports): array
    {
        $result = [];
        foreach ($transferReports as $transfer) {
            $transferResult['status'] = $transfer->getIsSuccess() ? 'success' : 'failed';

            foreach ($transfer->getDataImporterReports() as $reportTransfer) {
                $transferResult['type'] = $reportTransfer->getImportType();
                $transferResult['importedCount'] = $reportTransfer->getImportedDataSetCount();
                $transferResult['failed'] = $reportTransfer->getExpectedImportableDataSetCount() -
                    $reportTransfer->getImportedDataSetCount();

                $transferResult['messages'] = $this->getMessages($reportTransfer->getMessages());
            }
            $result[] = $transferResult;
        }

        return $result;
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
