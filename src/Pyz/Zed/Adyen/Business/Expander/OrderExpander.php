<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Expander;

use Generated\Shared\Transfer\AdyenPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface
     */
    private $adyenRepository;

    /**
     * @param \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface $adyenRepository
     */
    public function __construct(AdyenRepositoryInterface $adyenRepository)
    {
        $this->adyenRepository = $adyenRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAdyenPayment(OrderTransfer $orderTransfer): OrderTransfer
    {
        $paymentAdyenTransfer = $this->adyenRepository
            ->getPaymentAdyenByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $adyenPaymentTransfer = (new AdyenPaymentTransfer())
            ->setReference($paymentAdyenTransfer->getReference());

        return $orderTransfer->setAdyenPayment($adyenPaymentTransfer);
    }
}
