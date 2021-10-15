<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ApiLog\Writer;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Pyz\Client\Queue\QueueClientInterface;
use Pyz\Shared\ApiLog\ApiLogConfig;
use Pyz\Zed\ApiLog\Communication\Plugin\Event\Listener\NewApiInboundLogBulkListener;
use Pyz\Zed\ApiLog\Communication\Plugin\Event\Listener\NewApiOutboundLogBulkListener;
use Pyz\Zed\ApiLog\Dependency\ApiLogEvents;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ApiLogWriter implements ApiLogWriterInterface
{
    /**
     * @var \Pyz\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @param \Pyz\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(QueueClientInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return void
     */
    public function createApiOutboundLogViaQueue(ApiOutboundLogTransfer $apiOutboundLogTransfer): void
    {
        $queueSendMessageTransfer = $this->getQueueSendMessageTransfer(
            $apiOutboundLogTransfer,
            ApiLogEvents::NEW_OUTBOUND_LOG,
            NewApiOutboundLogBulkListener::class
        );
        $this->queueClient->sendMessage(ApiLogConfig::API_LOG_QUEUE, $queueSendMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return void
     */
    public function createApiInboundLogViaQueue(ApiInboundLogTransfer $apiInboundLogTransfer): void
    {
        $queueSendMessageTransfer = $this->getQueueSendMessageTransfer(
            $apiInboundLogTransfer,
            ApiLogEvents::NEW_INBOUND_LOG,
            NewApiInboundLogBulkListener::class
        );
        $this->queueClient->sendMessageViaZed(ApiLogConfig::API_LOG_QUEUE, $queueSendMessageTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $apiLogTransfer
     * @param string $eventName
     * @param string $listenerClassName
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    protected function getQueueSendMessageTransfer(
        AbstractTransfer $apiLogTransfer,
        string $eventName,
        string $listenerClassName
    ): QueueSendMessageTransfer {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setEvent($eventName)
            ->setOriginalValues([
                get_class($apiLogTransfer) => $apiLogTransfer->toArray(),
            ]);

        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->setBody(json_encode([
            EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => $listenerClassName,
            EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => get_class($eventEntityTransfer),
            EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $eventEntityTransfer->toArray(),
            EventQueueSendMessageBodyTransfer::EVENT_NAME => $eventName,
        ]));

        return $queueSendMessageTransfer;
    }
}
