<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\AdyenCreditCard3dSecureStep;
use Pyz\Yves\CheckoutPage\Process\Steps\CustomerStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ErrorStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use Pyz\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerShopStepFactory;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFactory extends SprykerShopStepFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function getSteps(): array
    {
        return [
            $this->createEntryStep(),
            $this->createPyzCustomerStep(),
            $this->createAddressStep(),
            $this->createPyzShipmentStep(),
            $this->createPaymentStep(),
            $this->createSummaryStep(),
            $this->createPlaceOrderStep(),
            $this->createAdyenCreditCard3dSecureStep(),
            $this->createSuccessStep(),
            $this->createErrorStep(),
        ];
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
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    public function createPaymentStep()
    {
        return new PaymentStep(
            $this->getPaymentClient(),
            $this->getPaymentMethodHandler(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getFlashMessenger(),
            $this->getCalculationClient(),
            $this->getCheckoutPaymentStepEnterPreCheckPlugins(),
            $this->createProductSellableChecker()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep
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
            ]
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
            $this->getConfig()->getEscapeRoute()
        );
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
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
    public function createStepCollection()
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
}
