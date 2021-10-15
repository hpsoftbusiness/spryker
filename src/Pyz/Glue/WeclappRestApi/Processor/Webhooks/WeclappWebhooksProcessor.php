<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi\Processor\Webhooks;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Queue\QueueClientInterface;
use Pyz\Glue\WeclappRestApi\WeclappRestApiConfig;
use Pyz\Shared\Weclapp\WeclappConfig;
use Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener\WeclappShipmentChangeBulkListener;
use Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener\WeclappStockChangeBulkListener;
use Pyz\Zed\Weclapp\Dependency\WeclappEvents;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class WeclappWebhooksProcessor implements WeclappWebhooksProcessorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Pyz\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Pyz\Client\ApiLog\ApiLogClientInterface
     */
    protected $apiLogClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Pyz\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Client\ApiLog\ApiLogClientInterface $apiLogClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QueueClientInterface $queueClient,
        ApiLogClientInterface $apiLogClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->queueClient = $queueClient;
        $this->apiLogClient = $apiLogClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processWeclappWebhook(
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer,
        Request $request
    ): RestResponseInterface {
        $startTimestamp = microtime(true);

        switch ($restWeclappWebhooksAttributesTransfer->getEntityName()) {
            case WeclappRestApiConfig::WEBHOOK_ENTITY_NAME_WAREHOUSE_STOCK:
                $this->addWebhookToQueue(
                    $restWeclappWebhooksAttributesTransfer,
                    WeclappEvents::STOCK_CHANGE,
                    WeclappStockChangeBulkListener::class
                );
                break;
            case WeclappRestApiConfig::WEBHOOK_ENTITY_NAME_SHIPMENT:
                $this->addWebhookToQueue(
                    $restWeclappWebhooksAttributesTransfer,
                    WeclappEvents::SHIPMENT_CHANGE,
                    WeclappShipmentChangeBulkListener::class
                );
                break;
        }

        $this->createApiInboundLog($request, $startTimestamp);

        return new RestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     * @param string $eventName
     * @param string $eventListenerClassName
     *
     * @return void
     */
    protected function addWebhookToQueue(
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer,
        string $eventName,
        string $eventListenerClassName
    ): void {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setEvent($eventName)
            ->setOriginalValues([
                RestWeclappWebhooksAttributesTransfer::class => $restWeclappWebhooksAttributesTransfer->toArray(),
            ]);

        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->setBody(json_encode([
            EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => $eventListenerClassName,
            EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => get_class($eventEntityTransfer),
            EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $eventEntityTransfer->toArray(),
            EventQueueSendMessageBodyTransfer::EVENT_NAME => $eventName,
        ]));

        $this->queueClient->sendMessageViaZed(WeclappConfig::WECLAPP_QUEUE, $queueSendMessageTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param float $startTimestamp
     *
     * @return void
     */
    protected function createApiInboundLog(
        Request $request,
        float $startTimestamp
    ): void {
        $apiInboundLogTransfer = new ApiInboundLogTransfer();
        $apiInboundLogTransfer->setMethod($request->getMethod())
            ->setUrl($request->getUri())
            ->setRequestHeaders(json_encode($request->headers->all()))
            ->setRequestBody($request->getContent())
            ->setTime((int)((microtime(true) - $startTimestamp) * 1000));

        $this->apiLogClient->createApiInboundLogViaQueue($apiInboundLogTransfer);
    }
}
