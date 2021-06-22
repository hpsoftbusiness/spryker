<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Pyz\Zed\Payment\Persistence\PaymentRepositoryInterface;
use Pyz\Zed\Sales\SalesConfig;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Zed\Payment\Business\Order\SalesPaymentHydrator as SprykerSalesPaymentHydrator;
use Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface;
use Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface;

class SalesPaymentHydrator extends SprykerSalesPaymentHydrator
{
    /**
     * @var \Pyz\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface $paymentHydratePluginCollection
     * @param \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface $paymentQueryContainer
     * @param \Pyz\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(
        PaymentHydratorPluginCollectionInterface $paymentHydratePluginCollection,
        PaymentQueryContainerInterface $paymentQueryContainer,
        PaymentRepositoryInterface $paymentRepository
    ) {
        parent::__construct(
            $paymentHydratePluginCollection,
            $paymentQueryContainer
        );

        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithPayment(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $salesPayments = $this->findSalesPaymentByIdSalesOrder($orderTransfer);
        $orderTransfer = $this->hydrate($salesPayments, $orderTransfer);
        $this->setMainOrderPayment($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPayment $salesPaymentEntity
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function mapPaymentTransfer(SpySalesPayment $salesPaymentEntity)
    {
        $paymentTransfer = parent::mapPaymentTransfer($salesPaymentEntity);
        if ($paymentTransfer->getPaymentProvider() === NopaymentConfig::PAYMENT_PROVIDER_NAME) {
            return $paymentTransfer;
        }

        $paymentMethodName = $this->paymentRepository->findPaymentMethodByKey($salesPaymentEntity->getSalesPaymentMethodType()->getPaymentMethod())->getName();
        $paymentTransfer->setPaymentMethodName($paymentMethodName);

        return $paymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    private function setMainOrderPayment(OrderTransfer $orderTransfer): void
    {
        if ($orderTransfer->getPayments()->count() === 0) {
            return;
        }

        foreach (SalesConfig::MAIN_PAYMENT_METHOD_PRIORITY_LIST as $mainPaymentMethodName) {
            if ($mainPaymentMethod = $this->findPaymentMethodByName($orderTransfer->getPayments(), $mainPaymentMethodName)) {
                $orderTransfer->setMainPayment($mainPaymentMethod);

                return;
            }
        }

        $orderTransfer->setMainPayment($orderTransfer->getPayments()[0]);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     * @param string $paymentMethodName
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    private function findPaymentMethodByName(ArrayObject $paymentTransfers, string $paymentMethodName): ?PaymentTransfer
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethod() === $paymentMethodName) {
                return $paymentTransfer;
            }
        }

        return null;
    }
}
