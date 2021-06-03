<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business\QueueDumper;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Spryker\Zed\Queue\Business\QueueDumper\QueueDumper;

class QueueDumperForQueueChecker extends QueueDumper
{
    /**
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueDumpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function dumpQueue(QueueDumpRequestTransfer $queueDumpRequestTransfer): QueueDumpResponseTransfer
    {
        $queueDumpResponseTransfer = $this->createQueueDumpResponseTransfer();
        $queueName = $queueDumpRequestTransfer->getQueueName();
        $limit = $queueDumpRequestTransfer->getLimit();
        $format = $queueDumpRequestTransfer->getFormat();
        $acknowledge = $queueDumpRequestTransfer->getAcknowledge();

        $queueReceiveMessageTransfers = $this->receiveQueueMessages($queueName, $limit);

        if ($queueReceiveMessageTransfers === []) {
            return $queueDumpResponseTransfer;
        }

        $data = $this->transformQueueReceiveMessageTransfersToArray($queueReceiveMessageTransfers);

        $queueDumpResponseTransfer->setMessage(
            $this->utilEncodingService->encodeToFormat($data, $format)
        );

        $this->postProcessMessages($queueReceiveMessageTransfers, $acknowledge);

        return $queueDumpResponseTransfer;
    }
}
