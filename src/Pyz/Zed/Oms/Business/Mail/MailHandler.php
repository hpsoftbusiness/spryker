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
use Pyz\Zed\Oms\Communication\Plugin\Mail\OrderInProcessingMailTypePlugin;
use Pyz\Zed\Oms\Communication\Plugin\Mail\ShippingConfirmationMailTypePlugin;
use Spryker\Zed\Oms\Business\Mail\MailHandler as SprykerMailHandler;
use Swift_TransportException;

class MailHandler extends SprykerMailHandler
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        try {
            parent::sendOrderConfirmationMail($salesOrderEntity);
        } catch (Swift_TransportException $e) {}
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

        $this->mailFacade->handleMail($mailTransfer);
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

        if ($countryEntity) {
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

        if ($countryEntity) {
            $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
            $addressTransfer->setCountry($countryTransfer);
        }

        return $addressTransfer;
    }
}
