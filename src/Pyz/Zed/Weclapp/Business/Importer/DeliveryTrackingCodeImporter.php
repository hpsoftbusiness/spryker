<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Importer;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Generated\Shared\Transfer\WeclappShipmentTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Pyz\Zed\Oms\Business\OmsFacadeInterface;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Pyz\Zed\Sales\Communication\Plugin\Command\SaveDeliveryTrackingCodeCommandPlugin;
use Pyz\Zed\Weclapp\WeclappConfig;

class DeliveryTrackingCodeImporter implements DeliveryTrackingCodeImporterInterface
{
    protected const COMMENT_USERNAME = 'Weclapp';

    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Pyz\Zed\Weclapp\WeclappConfig
     */
    protected $weclappConfig;

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Pyz\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Pyz\Zed\Weclapp\WeclappConfig $weclappConfig
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Pyz\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        WeclappConfig $weclappConfig,
        SalesFacadeInterface $salesFacade,
        OmsFacadeInterface $omsFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->weclappConfig = $weclappConfig;
        $this->salesFacade = $salesFacade;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function saveDeliveryTrackingCodesByWeclapp(array $restWeclappWebhooksAttributesTransfer): void
    {
        foreach ($restWeclappWebhooksAttributesTransfer as $restWeclappWebhookAttributesTransfer) {
            $weclappShipmentTransfer = $this->getWeclappShipment($restWeclappWebhookAttributesTransfer);
            if ($weclappShipmentTransfer
                && $weclappShipmentTransfer->getSalesOrderNumber()
                && $weclappShipmentTransfer->getPackageTrackingUrl()
                && $orderItemsIds = $this->salesFacade
                    ->getOrderItemsIdsByOrderReference($weclappShipmentTransfer->getSalesOrderNumber())
            ) {
                $this->omsFacade->triggerEventForOrderItems(
                    $this->weclappConfig->getShipTheOrderEvent(),
                    $orderItemsIds,
                    [SaveDeliveryTrackingCodeCommandPlugin::COMMENT_TRANSFER_KEY => $this->getCommentTransfer($weclappShipmentTransfer)]
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappShipmentTransfer|null
     */
    protected function getWeclappShipment(
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
    ): ?WeclappShipmentTransfer {
        $weclappShipmentTransfer = new WeclappShipmentTransfer();
        $weclappShipmentTransfer->setId($restWeclappWebhooksAttributesTransfer->getEntityIdOrFail());

        return $this->weclappClient->getShipment($weclappShipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappShipmentTransfer $weclappShipmentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function getCommentTransfer(WeclappShipmentTransfer $weclappShipmentTransfer): CommentTransfer
    {
        $commentTransfer = new CommentTransfer();
        $commentTransfer->setMessage($weclappShipmentTransfer->getPackageTrackingUrlOrFail())
            ->setUsername(static::COMMENT_USERNAME);

        return $commentTransfer;
    }
}
