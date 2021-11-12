<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Client\Quote\QuoteClientInterface;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\AdyenCreditCard3dSecureStep;
use Pyz\Yves\CheckoutPage\Process\Steps\BenefitDealStep;
use Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\CustomerStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ErrorStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep\PaymentPreConditionChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PostConditionChecker as SummaryStepPostConditionChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PreConditionChecker as SummaryStepPreConditionChecker;
use Pyz\Yves\StepEngine\Process\StepCollection;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin as SprykerShopCheckoutPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerShopStepFactory;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFactory extends SprykerShopStepFactory
{
    /**
     * @return \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface
     */
    protected function getMyWorldPaymentClient(): MyWorldPaymentClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::MY_WORLD_PAYMENT_CLIENT);
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface
     */
    protected function getLocalClient(): CheckoutPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return array_filter(
            [
                $this->createEntryStep(),
                $this->createPyzCustomerStep(),
                $this->createAddressStep(),
                $this->createPyzShipmentStep(),
                $this->getConfig()->isBenefitDealFeatureEnabled() ? $this->createBenefitStep() : null,
                $this->createPaymentStep(),
                $this->createSummaryStep(),
                $this->createPlaceOrderStep(),
                $this->createAdyenCreditCard3dSecureStep(),
                $this->createSuccessStep(),
                $this->createErrorStep(),
            ]
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    public function createSummaryStep()
    {
        return new SummaryStep(
            $this->getProductBundleClient(),
            $this->getShipmentService(),
            $this->getConfig(),
            SprykerShopCheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutClient(),
            $this->createSummaryStepPreConditionChecker(),
            $this->createSummaryStepPostConditionChecker(),
            $this->getPyzQuoteClient(),
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\CustomerStep
     */
    public function createPyzCustomerStep()
    {
        return new CustomerStep(
            $this->getCustomerClient(),
            $this->getCustomerStepHandler(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_CUSTOMER,
            $this->getConfig()->getEscapeRoute(),
            $this->getRouter()->generate(static::ROUTE_LOGOUT)
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep
     */
    public function createPyzShipmentStep()
    {
        return new ShipmentStep(
            $this->getCalculationClient(),
            $this->getShipmentPlugins(),
            $this->createShipmentStepPostConditionChecker(),
            $this->createGiftCardItemsChecker(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutShipmentStepEnterPreCheckPlugins(),
            $this->createProductSellableChecker(),
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\BenefitDealStep
     */
    public function createBenefitStep()
    {
        return new BenefitDealStep(
            $this->getPyzCustomerService(),
            $this->createBenefitDealBreadcrumbStatusChecker(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_BENEFIT,
            $this->getConfig()->getEscapeRoute()
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface
     */
    public function createBenefitDealBreadcrumbStatusChecker(): BreadcrumbStatusCheckerInterface
    {
        return new BreadcrumbStatusChecker(
            [
                $this->createAddressStepPostConditionChecker(),
                $this->createShipmentStepPostConditionChecker(),
            ]
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    public function createPaymentStep()
    {
        return new PaymentStep(
            $this->getTranslatorService(),
            $this->getMyWorldPaymentClient(),
            $this->getPaymentClient(),
            $this->getPaymentMethodHandler(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getFlashMessenger(),
            $this->getCalculationClient(),
            $this->getCheckoutPaymentStepEnterPreCheckPlugins(),
            $this->createProductSellableChecker(),
            $this->createPaymentPreConditionChecker(),
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep
     */
    public function createPlaceOrderStep()
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessenger(),
            $this->getStore()->getCurrentLocale(),
            $this->getGlossaryStorageClient(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER,
            $this->getConfig()->getEscapeRoute(),
            $this->createProductSellableChecker(),
            [
                static::ERROR_CODE_GENERAL_FAILURE => self::ROUTE_CART,
                'payment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
                'shipment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            ],
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createAdyenCreditCard3dSecureStep(): StepInterface
    {
        return new AdyenCreditCard3dSecureStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ADYEN_3D_SECURE,
            $this->getConfig()->getEscapeRoute(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createErrorStep(): StepInterface
    {
        return new ErrorStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ERROR,
            $this->getConfig()->getEscapeRoute(),
            $this->getPyzQuoteClient()
        );
    }

    /**
     * @return \Spryker\Shared\Translator\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_TRANSLATOR);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface
     */
    public function createProductSellableChecker(): ProductSellableCheckerInterface
    {
        return new ProductSellableChecker(
            $this->getFlashMessenger(),
            $this->getTranslatorService()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function createStepCollection(): StepCollectionInterface
    {
        return new StepCollection(
            $this->getRouter(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ERROR,
            [
                CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER,
                CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUCCESS,
            ]
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface
     */
    public function createSummaryStepPreConditionChecker(): PreConditionCheckerInterface
    {
        return new SummaryStepPreConditionChecker();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    public function createSummaryStepPostConditionChecker(): PostConditionCheckerInterface
    {
        return new SummaryStepPostConditionChecker(
            $this->getMyWorldPaymentClient(),
            $this->getFlashMessenger(),
            $this->getTranslatorService()
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface
     */
    public function createPaymentPreConditionChecker(): PreConditionCheckerInterface
    {
        return new PaymentPreConditionChecker(
            $this->getLocale(),
            $this->getFlashMessenger(),
            $this->getTranslatorService(),
            $this->getPyzCustomerService(),
            $this->createIntegerToDecimalConverter()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    private function createIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return new IntegerToDecimalConverter();
    }

    /**
     * @return \Pyz\Service\Customer\CustomerServiceInterface
     */
    public function getPyzCustomerService(): CustomerServiceInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_CUSTOMER);
    }

    /**
     * @return \Pyz\Client\Quote\QuoteClientInterface
     */
    public function getPyzQuoteClient(): QuoteClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PYZ_CLIENT_QUOTE);
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_LOCALE);
    }
}
