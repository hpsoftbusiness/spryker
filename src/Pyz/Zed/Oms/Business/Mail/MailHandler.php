<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\Mail;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Psr\Log\LoggerInterface;
use Pyz\Zed\Oms\Communication\Plugin\Mail\OrderInProcessingMailTypePlugin;
use Pyz\Zed\Oms\Communication\Plugin\Mail\ShippingConfirmationMailTypePlugin;
use Spryker\Zed\Oms\Business\Mail\MailHandler as SprykerMailHandler;
use Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;
use Swift_TransportException;

class MailHandler extends SprykerMailHandler
{
    private const SHIPPING_CONFIRMATION_SWIFT_TRANSPORT_EXCEPTION = 'Shipping confirmation email: %s';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface $saleFacade
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface $mailFacade
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[] $orderMailExpanderPlugins
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        OmsToSalesInterface $saleFacade,
        OmsToMailInterface $mailFacade,
        array $orderMailExpanderPlugins,
        LoggerInterface $logger
    ) {
        parent::__construct($saleFacade, $mailFacade, $orderMailExpanderPlugins);
        $this->logger = $logger;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        try {
            parent::sendOrderConfirmationMail($salesOrderEntity);
        } catch (Swift_TransportException $e) {
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendShippingConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $mailTransfer = (new MailTransfer())
            ->setOrder($orderTransfer)
            ->setType(ShippingConfirmationMailTypePlugin::MAIL_TYPE)
            ->setLocale($orderTransfer->getLocale());

        $mailTransfer = $this->expandOrderMailTransfer($mailTransfer, $orderTransfer);

        try {
            $this->mailFacade->handleMail($mailTransfer);
        } catch (Swift_TransportException $exception) {
            $this->logger->error(
                sprintf(self::SHIPPING_CONFIRMATION_SWIFT_TRANSPORT_EXCEPTION, $exception->getMessage()),
                ['exception' => $exception]
            );
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderInProcessingMail(SpySalesOrder $salesOrderEntity): void
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $mailTransfer = (new MailTransfer())
            ->setOrder($orderTransfer)
            ->setType(OrderInProcessingMailTypePlugin::MAIL_TYPE)
            ->setLocale($orderTransfer->getLocale());

        $mailTransfer = $this->expandOrderMailTransfer($mailTransfer, $orderTransfer);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function mapShippingAddressEntityToShippingAddressTransfer(SpySalesOrder $salesOrderEntity): ?AddressTransfer
    {
        $shippingAddressEntity = $salesOrderEntity->getShippingAddress();

        if ($shippingAddressEntity === null) {
            return null;
        }

        $countryEntity = $shippingAddressEntity->getCountry();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($shippingAddressEntity->toArray(), true);

        if ($countryEntity !== null) {
            $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
            $addressTransfer->setCountry($countryTransfer);
        }

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getBillingAddressTransfer(SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $salesOrderEntity->getBillingAddress();
        $countryEntity = $billingAddressEntity->getCountry();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($billingAddressEntity->toArray(), true);

        if ($countryEntity !== null) {
            $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
            $addressTransfer->setCountry($countryTransfer);
        }

        return $addressTransfer;
    }
}
