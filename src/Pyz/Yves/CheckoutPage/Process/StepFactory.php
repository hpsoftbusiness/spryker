<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\AdyenCreditCard3dSecureStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerShopStepFactory;
use SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetDependencyProvider;
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
            $this->createCustomerStep(),
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
            $this->getFlashMessenger(),
            $this->getTranslatorService()
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
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::SERVICE_TRANSLATOR);
    }
}
