<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginWithMessengerInterface;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep as SprykerShopPaymentStep;
use Symfony\Component\HttpFoundation\Request;

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
     * @var \Spryker\Shared\Translator\TranslatorInterface
     */
    private $translator;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface
     */
    private $preConditionChecker;

    /**
     * @param \Spryker\Shared\Translator\TranslatorInterface $translator
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface $myWorldPaymentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $paymentPlugins
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param array $checkoutPaymentStepEnterPreCheckPlugins
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface $productSellableChecker
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface $preConditionChecker
     */
    public function __construct(
        TranslatorInterface $translator,
        MyWorldPaymentClientInterface $myWorldPaymentClient,
        CheckoutPageToPaymentClientInterface $paymentClient,
        StepHandlerPluginCollection $paymentPlugins,
        string $stepRoute,
        string $escapeRoute,
        FlashMessengerInterface $flashMessenger,
        CheckoutPageToCalculationClientInterface $calculationClient,
        array $checkoutPaymentStepEnterPreCheckPlugins,
        ProductSellableCheckerInterface $productSellableChecker,
        PreConditionCheckerInterface $preConditionChecker
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
        $this->translator = $translator;
        $this->preConditionChecker = $preConditionChecker;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer): bool
    {
        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer): bool
    {
        $isQuoteValid = parent::preCondition($quoteTransfer);
        $isQuoteValid = $this->productSellableChecker->check($quoteTransfer, $isQuoteValid);

        if (!$isQuoteValid) {
            $this->escapeRoute = CartPageRouteProviderPlugin::ROUTE_NAME_CART;
        }

        if (!$this->preConditionChecker->check($quoteTransfer)) {
            $this->escapeRoute = CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_BENEFIT;

            return false;
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
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $isQuoteValid = parent::postCondition($quoteTransfer);

        if ($isQuoteValid) {
            /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
            if ($this->isInternalPaymentMethodSelected($quoteTransfer) &&
                !$quoteTransfer->getMyWorldPaymentSessionId()) {
                $isQuoteValid = false;
            }
        }

        return $isQuoteValid;
    }

    /**
     * Specification:
     *  Method using at validation of the payment. It overrides for accept Nopayment method
     *  in case the price to pay was covered by internal payments
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer[]|\ArrayObject $paymentCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isValidPaymentSelection(ArrayObject $paymentCollection, QuoteTransfer $quoteTransfer): bool
    {
        $paymentMethods = $this->paymentClient->getAvailableMethods($quoteTransfer);

        foreach ($paymentCollection as $candidatePayment) {
            if (!$this->containsPayment($paymentMethods, $candidatePayment)) {
                if ($candidatePayment->getPaymentProvider() === NopaymentConfig::PAYMENT_PROVIDER_NAME) {
                    return $this->isInternalPaymentsCoverPriceToPay($quoteTransfer);
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if (!$this->executeCheckoutPaymentStepEnterPreCheckPlugins($quoteTransfer)) {
            return $quoteTransfer;
        }

        /**
         *  Recalculating quote totals with selected MyWorld payment methods (necessary for NoPayment method).
         */
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
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

            /**
             * Recalculating quote with payment handler changes.
             */
            $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
            $quoteTransfer = $this->handlePaymentSessionCreation($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $abstractTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected function handlePaymentSessionCreation(QuoteTransfer $abstractTransfer): ?AbstractTransfer
    {
        if ($this->isInternalPaymentMethodSelected($abstractTransfer)) {
            $paymentSessionResponse = $this->myWorldPaymentClient->createPaymentSession($abstractTransfer);

            if ($paymentSessionResponse->getIsSuccess()) {
                $paymentSessionResponse->requirePaymentSessionResponse();
                $paymentSessionResponse->getPaymentSessionResponse()->requireSessionId();
                $abstractTransfer->setMyWorldPaymentSessionId(
                    $paymentSessionResponse->getPaymentSessionResponse()->getSessionId()
                );
                $abstractTransfer->setMyWorldPaymentIsSmsAuthenticationRequired(
                    in_array('SMS', $paymentSessionResponse->getPaymentSessionResponse()->getTwoFactorAuth())
                );
            } elseif ($this->isCustomerHasAmountOfVouchers($abstractTransfer->getCustomer()) &&
                !$paymentSessionResponse->getIsSuccess()) {
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
     * @return bool
     */
    protected function isInternalPaymentMethodSelected(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                return true;
            }
        }

        return $quoteTransfer->getTotalUsedShoppingPointsAmount() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isInternalPaymentsCoverPriceToPay(QuoteTransfer $quoteTransfer): bool
    {
        if (!$this->isInternalPaymentMethodSelected($quoteTransfer)) {
            return false;
        }

        $internalAmount = array_reduce(
            $quoteTransfer->getPayments()->getArrayCopy(),
            function (int $carry, PaymentTransfer $paymentTransfer) {
                if ($paymentTransfer->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                    $carry += $paymentTransfer->getAmount();
                }

                return $carry;
            },
            0
        );

        return $quoteTransfer->getTotals()->getGrandTotal() <= $internalAmount;
    }
}
