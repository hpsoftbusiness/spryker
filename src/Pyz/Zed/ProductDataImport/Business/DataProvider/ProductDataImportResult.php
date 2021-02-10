<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\DataProvider;

class ProductDataImportResult
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer[] $transferReports
     *
     * @return string
     */
    public function collectionDataImporterReportTransferToString(array $transferReports): string
    {
        $result = [];
        foreach ($transferReports as $transfer) {
            $transferResult['status'] = $transfer->getIsSuccess() ? 'success' : 'failed';

            foreach ($transfer->getDataImporterReports() as $reportTransfer) {
                $transferResult['type'] = $reportTransfer->getImportType();
                $transferResult['importedCount'] = $reportTransfer->getImportedDataSetCount();
                $transferResult['failed'] = $reportTransfer->getExpectedImportableDataSetCount(
                ) - $reportTransfer->getImportedDataSetCount();

                $messages = [];

                foreach ($reportTransfer->getMessages() as $message) {
//                    in message we have string that contain exception trace that we dont need
                    $strMessage = strstr($message->getMessage(), "For debugging execute", true);
                    $messages[] = ($strMessage) ? $strMessage : $message->getMessage();
                }
                $transferResult['messages'] = $messages;
            }
            $result[] = $transferResult;
        }

        return json_encode($result);
    }
}
