<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QueueErrorChecker\Business\Model;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Pyz\Zed\Queue\Business\QueueFacadeInterface;

class QueueErrorMessenger implements QueueErrorMessengerInterface
{
    private const DUMP_REQUEST_FORMAT = 'json';
    /**
     * @var \Pyz\Zed\Queue\Business\QueueFacadeInterface
     */
    private $queueFacade;

    /**
     * @param \Pyz\Zed\Queue\Business\QueueFacadeInterface $queueFacade
     */
    public function __construct(QueueFacadeInterface $queueFacade)
    {
        $this->queueFacade = $queueFacade;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(string $queueName, int $limit): array
    {
        $messagesArray = [];
        $queueDumpResponseTransfer = $this->queueFacade->queueDumpForQueueChecker(
            $this->generateQueueDumpRequestTransfer($queueName, $limit)
        );

        if ($queueDumpResponseTransfer->getMessage() !== null) {
            $queueDumpResponseMessagesArray = json_decode($queueDumpResponseTransfer->getMessage(), true);

            foreach ($queueDumpResponseMessagesArray as $queueDumpResponseMessage) {
                if (isset($queueDumpResponseMessage['queue_message']) &&
                    $queueDumpResponseMessage['queue_message']['body']) {
                    $messagesArray[] = $queueDumpResponseMessage['queue_message']['body'];
                }
            }
        }

        return $messagesArray;
    }

    /**
     * @param string $queueName
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\QueueDumpRequestTransfer
     */
    private function generateQueueDumpRequestTransfer(string $queueName, int $limit): QueueDumpRequestTransfer
    {
        $queueDumpRequestTransfer = new QueueDumpRequestTransfer();
        $queueDumpRequestTransfer->setLimit($limit);
        $queueDumpRequestTransfer->setFormat(self::DUMP_REQUEST_FORMAT);
        $queueDumpRequestTransfer->setAcknowledge(false);
        $queueDumpRequestTransfer->setQueueName($queueName);

        return $queueDumpRequestTransfer;
    }
}
