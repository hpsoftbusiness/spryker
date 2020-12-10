<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesInvoice\Business\Expander;

use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface
     */
    private $salesInvoiceRepository;

    /**
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface $salesInvoiceRepository
     */
    public function __construct(SalesInvoiceRepositoryInterface $salesInvoiceRepository)
    {
        $this->salesInvoiceRepository = $salesInvoiceRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOrderInvoices(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setSalesOrderIds([$orderTransfer->getIdSalesOrder()]);
        $orderInvoiceCollectionTransfer = $this->salesInvoiceRepository
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);

        return $orderTransfer->setOrderInvoices($orderInvoiceCollectionTransfer->getOrderInvoices());
    }
}
