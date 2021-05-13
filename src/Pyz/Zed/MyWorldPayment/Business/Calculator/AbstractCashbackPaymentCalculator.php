<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

abstract class AbstractCashbackPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $myWorldPaymentConfig;

    /**
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     */
    public function __construct(
        CustomerServiceInterface $customerService,
        MyWorldPaymentConfig $myWorldPaymentConfig
    ) {
        $this->customerService = $customerService;
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $customer = $calculableObjectTransfer->getOriginalQuote()->getCustomer();
        if (!$customer) {
            return $calculableObjectTransfer;
        }

        $this->removePaymentMethod($calculableObjectTransfer);
        if (!$this->isPaymentSelected($calculableObjectTransfer)) {
            return $calculableObjectTransfer;
        }

        $customerAvailableBalance = $this->getCustomerBalanceAmount($customer);
        if ($customerAvailableBalance === 0) {
            return $calculableObjectTransfer;
        }

        $usableBalanceAmount = $this->calculateUsableBalanceAmount($customerAvailableBalance, $calculableObjectTransfer);
        $this->addPaymentToQuote($calculableObjectTransfer, $usableBalanceAmount);
        $this->setTotalUsedBalanceForQuote($calculableObjectTransfer, $usableBalanceAmount);

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
     *
     * @return void
     */
    protected function removePaymentMethod(
        CalculableObjectTransfer $calculableObjectTransfer
    ): void {
        foreach ($calculableObjectTransfer->getPayments() as $index => $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->getPaymentName()) {
                $calculableObjectTransfer->getPayments()->offsetUnset($index);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $usableBalanceAmount
     *
     * @return void
     */
    protected function addPaymentToQuote(
        CalculableObjectTransfer $calculableObjectTransfer,
        int $usableBalanceAmount
    ): void {
        $calculableObjectTransfer->addPayment(
            $this->createPaymentTransfer($usableBalanceAmount)
        );
    }

    /**
     * @param int $usableBalanceAmount
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer(
        int $usableBalanceAmount
    ): PaymentTransfer {
        $paymentName = $this->getPaymentName();

        return (new PaymentTransfer())
            ->setPaymentProvider($this->myWorldPaymentConfig->getMyWorldPaymentProviderKey())
            ->setPaymentSelection($paymentName)
            ->setPaymentMethod($paymentName)
            ->setPaymentMethodName($paymentName)
            ->setAvailableAmount($usableBalanceAmount)
            ->setAmount($usableBalanceAmount)
            ->setIsLimitedAmount(true);
    }

    /**
     * @param int $customerBalanceAmount
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateUsableBalanceAmount(int $customerBalanceAmount, CalculableObjectTransfer $calculableObjectTransfer): int
    {
        return min($customerBalanceAmount, $calculableObjectTransfer->getTotals()->getGrandTotal());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    abstract protected function getCustomerBalanceAmount(CustomerTransfer $customerTransfer): int;

    /**
     * @return string
     */
    abstract protected function getPaymentName(): string;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    abstract protected function isPaymentSelected(CalculableObjectTransfer $calculableObjectTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $usableBalanceAmount
     *
     * @return void
     */
    abstract protected function setTotalUsedBalanceForQuote(
        CalculableObjectTransfer $calculableObjectTransfer,
        int $usableBalanceAmount
    ): void;
}
