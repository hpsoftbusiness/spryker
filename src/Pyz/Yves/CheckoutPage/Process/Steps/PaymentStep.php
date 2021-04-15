<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginWithMessengerInterface;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep as SprykerShopPaymentStep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentStep extends SprykerShopPaymentStep
{
    protected const API_ERROR_MESSAGE = 'payment.step.api_request.error';

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface
     */
    protected $productSellableChecker;

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface
     */
    private $myWorldPaymentClient;

    /**
     * @var \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    private $integerToDecimalConverter;

    /**
     * @var \Spryker\Shared\Translator\TranslatorInterface
     */
    private $translator;

    /**
     * @var \Pyz\Yves\CheckoutPage\CheckoutPageConfig
     */
    private $checkoutPageConfig;

    /**
     * @param \Pyz\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface $myWorldPaymentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $paymentPlugins
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param array $checkoutPaymentStepEnterPreCheckPlugins
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface $productSellableChecker
     */
    public function __construct(
        CheckoutPageConfig $checkoutPageConfig,
        TranslatorInterface $translator,
        IntegerToDecimalConverterInterface $integerToDecimalConverter,
        MyWorldPaymentClientInterface $myWorldPaymentClient,
        CheckoutPageToPaymentClientInterface $paymentClient,
        StepHandlerPluginCollection $paymentPlugins,
        string $stepRoute,
        string $escapeRoute,
        FlashMessengerInterface $flashMessenger,
        CheckoutPageToCalculationClientInterface $calculationClient,
        array $checkoutPaymentStepEnterPreCheckPlugins,
        ProductSellableCheckerInterface $productSellableChecker
    ) {
        parent::__construct(
            $paymentClient,
            $paymentPlugins,
            $stepRoute,
            $escapeRoute,
            $flashMessenger,
            $calculationClient,
            $checkoutPaymentStepEnterPreCheckPlugins
        );

        $this->productSellableChecker = $productSellableChecker;
        $this->myWorldPaymentClient = $myWorldPaymentClient;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
        $this->translator = $translator;
        $this->checkoutPageConfig = $checkoutPageConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        $totals = $quoteTransfer->getTotals();
        $isNeedToPay = $this->executeCheckoutPaymentStepEnterPreCheckPlugins($quoteTransfer) && (!$totals || $totals->getPriceToPay() > 0);

        if (!$isNeedToPay) {
            return $quoteTransfer->getMyWorldUseEVoucherBalance();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        $isQuoteValid = parent::preCondition($quoteTransfer);
        $isQuoteValid = $this->productSellableChecker->check($quoteTransfer, $isQuoteValid);

        if (!$isQuoteValid) {
            $this->escapeRoute = CartPageRouteProviderPlugin::ROUTE_NAME_CART;
        }

        return $isQuoteValid;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        $isQuoteValid = parent::postCondition($quoteTransfer);

        if ($isQuoteValid) {
            if ($quoteTransfer->getMyWorldUseEVoucherBalance()
                && !$quoteTransfer->getMyWorldPaymentSessionId()
                && $this->isCustomerHasAmountOfVouchers($quoteTransfer->getCustomer())
            ) {
                $isQuoteValid = false;
            }
        }

        return $isQuoteValid;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if (!$this->executeCheckoutPaymentStepEnterPreCheckPlugins($quoteTransfer)) {
            return $quoteTransfer;
        }
        $paymentSelection = $this->getPaymentSelectionWithFallback($quoteTransfer);

        if ($paymentSelection === null) {
            return $quoteTransfer;
        }

        if ($this->paymentPlugins->has($paymentSelection)) {
            $paymentHandler = $this->paymentPlugins->get($paymentSelection);

            if ($paymentHandler instanceof StepHandlerPluginWithMessengerInterface) {
                $paymentHandler->setFlashMessenger($this->flashMessenger);
            }
            $paymentHandler->addToDataClass($request, $quoteTransfer);
            $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
            $quoteTransfer = $this->handlePaymentSessionCreation($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer): array
    {
        $myWorldMarketplaceClientBalance = $dataTransfer->getCustomer()->getCustomerBalance();

        $variables['clientsBalance'] = [
            'availableCashbackCurrency' => $myWorldMarketplaceClientBalance->getAvailableCashbackCurrency(),
            'availableBenefitVoucherCurrency' => $myWorldMarketplaceClientBalance->getAvailableBenefitVoucherCurrency(),
            'availableCashbackAmount' => $myWorldMarketplaceClientBalance->getAvailableCashbackAmount()->toFloat(),
            'availableShoppingPointAmount' => $myWorldMarketplaceClientBalance->getAvailableShoppingPointAmount()->toFloat(),
            'availableBenefitVoucherAmount' => $myWorldMarketplaceClientBalance->getAvailableBenefitVoucherAmount()->toFloat(),
            'availableEVouchersForCharge' => $this->getAvailableChargeAmount($dataTransfer),
        ];

        return $variables;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected function handlePaymentSessionCreation(QuoteTransfer $abstractTransfer): ?AbstractTransfer
    {
        if ($this->isInternalPaymentMethodSelected($abstractTransfer)) {
            $paymentSessionResponse = $this->myWorldPaymentClient->createPaymentSession($abstractTransfer);

            if ($paymentSessionResponse->getIsSuccess() && $this->isCustomerHasAmountOfVouchers($abstractTransfer->getCustomer())) {
                $paymentSessionResponse->requirePaymentSessionResponse();
                $paymentSessionResponse->getPaymentSessionResponse()->requireSessionId();
                $abstractTransfer->setMyWorldPaymentSessionId(
                    $paymentSessionResponse->getPaymentSessionResponse()->getSessionId()
                );
                $abstractTransfer->setMyWorldPaymentIsSmsAuthenticationRequired(
                    in_array('SMS', $paymentSessionResponse->getPaymentSessionResponse()->getTwoFactorAuth())
                );
            } elseif ($this->isCustomerHasAmountOfVouchers($abstractTransfer->getCustomer()) && !$paymentSessionResponse->getIsSuccess()) {
                $this->flashMessenger->addErrorMessage(
                    $this->translator->trans(static::API_ERROR_MESSAGE)
                );
            }
        }

        return $abstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return bool
     */
    protected function isCustomerHasAmountOfVouchers(CustomerTransfer $customer): bool
    {
        $availableCashbackAmount = $customer->getCustomerBalance()->getAvailableCashbackAmount();

        return $availableCashbackAmount->toFloat() !== 0.00;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getAvailableChargeAmount(QuoteTransfer $quoteTransfer): float
    {
        $amount = $this->getExistedAmountToCharge($quoteTransfer);

        if ($amount) {
            return (float)($amount / 100); // convert to float for show on page
        }

        $myWorldMarketplaceClientBalance = $quoteTransfer->getCustomer()->getCustomerBalance();

        return $quoteTransfer->getTotals()->getPriceToPay() >= (int)$myWorldMarketplaceClientBalance->getAvailableCashbackAmount()->toFloat() * 100
            ? $myWorldMarketplaceClientBalance->getAvailableCashbackAmount()->toFloat()
            : $this->integerToDecimalConverter->convert($quoteTransfer->getTotals()->getPriceToPay());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getExistedAmountToCharge(QuoteTransfer $quoteTransfer): ?int
    {
        $payment = $this->getEVoucherPaymentMethod($quoteTransfer);

        if ($payment) {
            return $payment->getAmount();
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function getEVoucherPaymentMethod(QuoteTransfer $quoteTransfer): ?PaymentTransfer
    {
        foreach ($quoteTransfer->getPayments() as $payment) {
            if ($payment->getPaymentMethodName() === $this->checkoutPageConfig->getEVoucherPaymentName()) {
                return $payment;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isInternalPaymentMethodSelected(QuoteTransfer $quoteTransfer): bool
    {
        return $this->isBenefitVoucherSelected($quoteTransfer) || $quoteTransfer->getMyWorldUseEVoucherBalance();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherSelected(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems()->getArrayCopy() as $item) {
            if ($item->getUseBenefitVoucher()) {
                return true;
            }
        }

        return false;
    }
}
