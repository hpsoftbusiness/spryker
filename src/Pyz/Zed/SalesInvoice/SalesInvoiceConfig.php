<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesInvoice;

use Spryker\Zed\SalesInvoice\SalesInvoiceConfig as SprykerSalesInvoiceConfig;

class SalesInvoiceConfig extends SprykerSalesInvoiceConfig
{
    public const ORDER_INVOICE_REFERENCE_PREFIX_NUMBER = 1279;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getOrderInvoiceTemplatePath(): string
    {
        return 'SalesInvoice/invoice/invoice.twig';
    }

    /**
     * @return string
     */
    protected function getOrderInvoiceReferencePrefix(): string
    {
        return parent::getOrderInvoiceReferencePrefix() . static::ORDER_INVOICE_REFERENCE_PREFIX_NUMBER;
    }
}
