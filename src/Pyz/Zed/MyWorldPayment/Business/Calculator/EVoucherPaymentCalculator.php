<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\PaymentPriceManagerInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class EVoucherPaymentCalculator implements MyWorldPaymentCalculatorInterface
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
     * @var \Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\PaymentPriceManagerInterface
     */
    private $paymentPriceManager;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $marketplaceApiClient
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     * @param \Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\PaymentPriceManagerInterface $paymentPriceManager
     */
    public function __construct(
        MyWorldMarketplaceApiClientInterface $marketplaceApiClient,
        MyWorldPaymentConfig $myWorldPaymentConfig,
        PaymentPriceManagerInterface $paymentPriceManager
    ) {
        $this->marketplaceApiClient = $marketplaceApiClient;
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->paymentPriceManager = $paymentPriceManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer = $this->removeEVoucherPayment($calculableObjectTransfer);

        if ($this->isEVoucherPaymentSelected($calculableObjectTransfer)) {
            $calculableObjectTransfer = $this->addPaymentToQuote($calculableObjectTransfer);
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
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function addPaymentToQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $clientBalance = $calculableObjectTransfer->getOriginalQuote()->getCustomer()->getCustomerBalance();

        if ($clientBalance->getAvailableCashbackAmount()->toFloat() === 0.00) {
            return $calculableObjectTransfer;
        }

        $availableVouchers = (int)($clientBalance->getAvailableCashbackAmount()->round(2)->toFloat() * 100);
        $availableAmountOfVouchers = $this->paymentPriceManager->getAvailablePriceToPayByCalculableObject($calculableObjectTransfer);

        $calculableObjectTransfer->addPayment(
            $this->createEVouchersPaymentTransfer(
                $availableVouchers,
                $availableAmountOfVouchers->getAvailableEVoucherToCharge()
            )
        );

        return $calculableObjectTransfer;
    }

    /**
     * @param int $availablePrice
     * @param int $chargePrice
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createEVouchersPaymentTransfer(int $availablePrice, int $chargePrice): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setPaymentProvider(SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD)
            ->setPaymentSelection($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setPaymentMethod($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setPaymentMethodName($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setAvailableAmount($chargePrice)
            ->setAmount($chargePrice)
            ->setIsLimitedAmount(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isEVoucherPaymentSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        return (bool)$calculableObjectTransfer->getMyWorldUseEVoucherBalance();
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function getEVoucherPayment(CalculableObjectTransfer $calculableObjectTransfer): ?PaymentTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->myWorldPaymentConfig->getOptionEVoucherName()) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function removeEVoucherPayment(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $index => $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->myWorldPaymentConfig->getOptionEVoucherName()) {
                unset($calculableObjectTransfer->getPayments()[$index]);
            }
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerInformation(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->marketplaceApiClient->getCustomerInformationByCustomerNumberOrId($customerTransfer);
    }
}
