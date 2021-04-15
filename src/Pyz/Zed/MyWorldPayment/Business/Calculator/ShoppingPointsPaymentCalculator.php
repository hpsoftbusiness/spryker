<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ShoppingPointsPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $config
     */
    public function __construct(MyWorldPaymentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $customerTransfer = $calculableObjectTransfer->getOriginalQuote()->getCustomer();
        if (!$customerTransfer) {
            return $calculableObjectTransfer;
        }

        $availableShoppingPointsAmount = $customerTransfer->getCustomerBalance()->getAvailableShoppingPointAmount()->toFloat();
        $totalShoppingPointsAmountSpent = 0;
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUseShoppingPoints() || $availableShoppingPointsAmount === 0) {
                continue;
            }

            $this->calculateItemUsedShoppingPoints($itemTransfer, $availableShoppingPointsAmount);
            $totalItemUsedShoppingPoints = $itemTransfer->getTotalUsedShoppingPointsAmount();
            $totalShoppingPointsAmountSpent += $totalItemUsedShoppingPoints;
            $availableShoppingPointsAmount -= $totalItemUsedShoppingPoints;
        }

        $calculableObjectTransfer->setTotalUsedShoppingPointsAmount($totalShoppingPointsAmountSpent);
        $paymentMethodName = $this->config->getShoppingPointsPaymentName();
        if ($totalShoppingPointsAmountSpent > 0) {
            $paymentTransfer = $this->findPayment($calculableObjectTransfer, $paymentMethodName);
            if (!$paymentTransfer) {
                $paymentTransfer = $this->createPayment($paymentMethodName);
                $calculableObjectTransfer->addPayment($paymentTransfer);
            }

            $paymentTransfer->setAmount($totalShoppingPointsAmountSpent);
        } else {
            $this->removePayment($calculableObjectTransfer, $paymentMethodName);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param string $paymentMethodName
     *
     * @return void
     */
    private function removePayment(CalculableObjectTransfer $calculableObjectTransfer, string $paymentMethodName): void
    {
        foreach ($calculableObjectTransfer->getPayments() as $key => $payment) {
            if ($payment->getPaymentMethodName() === $paymentMethodName) {
                $calculableObjectTransfer->getPayments()->offsetUnset($key);

                return;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param string $paymentMethodName
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    private function findPayment(CalculableObjectTransfer $calculableObjectTransfer, string $paymentMethodName): ?PaymentTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethodName() === $paymentMethodName) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $paymentMethodName
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function createPayment(string $paymentMethodName): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setPaymentProvider($this->config->getMyWorldPaymentProviderKey())
            ->setPaymentSelection($paymentMethodName)
            ->setPaymentMethod($paymentMethodName)
            ->setPaymentMethodName($paymentMethodName);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $availablePoints
     *
     * @return void
     */
    private function calculateItemUsedShoppingPoints(ItemTransfer $itemTransfer, float $availablePoints): void
    {
        $shoppingPointsDealTransfer = $itemTransfer->getShoppingPointsDeal();
        if (!$shoppingPointsDealTransfer || !$this->assertShoppingPointsDealTransfer($shoppingPointsDealTransfer)) {
            return;
        }

        $totalShoppingPointsForItem = $itemTransfer->getQuantity() * $shoppingPointsDealTransfer->getShoppingPointsQuantity();
        if ($totalShoppingPointsForItem > $availablePoints) {
            return;
        }

        $itemTransfer->setTotalUsedShoppingPointsAmount($totalShoppingPointsForItem);
        $itemTransfer->setUnitGrossPrice($shoppingPointsDealTransfer->getPrice());
        $itemTransfer->setSumGrossPrice($shoppingPointsDealTransfer->getPrice() * $itemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingPointsDealTransfer $shoppingPointsDealTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsDealTransfer(ShoppingPointsDealTransfer $shoppingPointsDealTransfer): bool
    {
        try {
            $shoppingPointsDealTransfer->requireIsActive();
            $shoppingPointsDealTransfer->requireShoppingPointsQuantity();
            $shoppingPointsDealTransfer->requirePrice();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }

        return $shoppingPointsDealTransfer->getShoppingPointsQuantity() > 0 && $shoppingPointsDealTransfer->getPrice() > 0;
    }
}
