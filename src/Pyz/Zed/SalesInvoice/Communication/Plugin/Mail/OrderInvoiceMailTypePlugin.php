<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesInvoice\Communication\Plugin\Mail;

use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\SalesInvoice\Communication\Plugin\Mail\OrderInvoiceMailTypePlugin as SprykerOrderInvoiceMailTypePlugin;

/**
 * @method \Pyz\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 */
class OrderInvoiceMailTypePlugin extends SprykerOrderInvoiceMailTypePlugin
{
    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\SalesInvoice\Communication\Plugin\Mail\OrderInvoiceMailTypePlugin
     */
    protected function setRecipient(MailBuilderInterface $mailBuilder)
    {
        foreach ($this->getConfig()->getOrderInvoiceTo() as $recipientTransfer) {
            $mailBuilder->addRecipient($recipientTransfer->getEmail(), $recipientTransfer->getName());
        }

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\SalesInvoice\Communication\Plugin\Mail\OrderInvoiceMailTypePlugin
     */
    protected function setSubject(MailBuilderInterface $mailBuilder)
    {
        $invoiceReference = $mailBuilder->getMailTransfer()->getOrderInvoice()->getReference();
        $orderTransfer = $mailBuilder->getMailTransfer()->getOrder();

        $mailBuilder->setSubject(static::GLOSSARY_KEY_MAIL_ORDER_INVOICE_SUBJECT, [
            '%invoiceReference%' => $invoiceReference,
            '%idOrder%' => $orderTransfer->getOrderReference(),
        ]);

        return $this;
    }
}
