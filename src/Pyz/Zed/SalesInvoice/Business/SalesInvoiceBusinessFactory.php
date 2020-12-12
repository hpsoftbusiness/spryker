<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesInvoice\Business;

use Pyz\Zed\SalesInvoice\Business\Expander\OrderExpander;
use Pyz\Zed\SalesInvoice\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\SalesInvoice\Business\SalesInvoiceBusinessFactory as SprykerSalesInvoiceBusinessFactory;

/**
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
 */
class SalesInvoiceBusinessFactory extends SprykerSalesInvoiceBusinessFactory
{
    /**
     * @return \Pyz\Zed\SalesInvoice\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->getRepository());
    }
}
