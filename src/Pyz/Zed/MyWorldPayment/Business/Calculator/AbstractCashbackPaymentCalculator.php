<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;

abstract class AbstractCashbackPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $marketplaceApiClient;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $myWorldPaymentConfig;

    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    protected $decimalToIntegerConverter;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $marketplaceApiClient
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     * @param \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface $decimalToIntegerConverter
     */
    public function __construct(
        MyWorldMarketplaceApiClientInterface $marketplaceApiClient,
        MyWorldPaymentConfig $myWorldPaymentConfig,
        DecimalToIntegerConverterInterface $decimalToIntegerConverter
    ) {
        $this->marketplaceApiClient = $marketplaceApiClient;
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->decimalToIntegerConverter = $decimalToIntegerConverter;
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
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[] $customerBalancesCollection
     * @param int $idPaymentOption
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer|null
     */
    protected function findBalanceByPaymentOptionId(
        array $customerBalancesCollection,
        int $idPaymentOption
    ): ?CustomerBalanceByCurrencyTransfer {
        foreach ($customerBalancesCollection as $balanceByCurrencyTransfer) {
            if ($balanceByCurrencyTransfer->getPaymentOptionId() === $idPaymentOption) {
                return $balanceByCurrencyTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    protected function getCustomerBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        $balances = $this->marketplaceApiClient->getCustomerBalanceByCurrency($customerTransfer);
        $eVoucherCustomerBalance = $this->findBalanceByPaymentOptionId(
            $balances,
            $this->getPaymentOptionId()
        );

        if (!$eVoucherCustomerBalance) {
            return 0;
        }

        return $this->decimalToIntegerConverter->convert(
            $eVoucherCustomerBalance->getTargetAvailableBalance()->toFloat()
        );
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
     * @return int
     */
    abstract protected function getPaymentOptionId(): int;

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
