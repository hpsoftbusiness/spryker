<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerShopStepFactory;
use SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetDependencyProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            $this->createPyzAddressStep(),
            $this->createShipmentStep(),
            $this->createPaymentStep(),
            $this->createSummaryStep(),
            $this->createPlaceOrderStep(),
            $this->createSuccessStep(),
            $this->createErrorStep(),
        ];
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\AddressStep
     */
    public function createPyzAddressStep(): AddressStep
    {
        return new AddressStep(
            $this->getCalculationClient(),
            $this->createAddressStepExecutor(),
            $this->createAddressStepPostConditionChecker(),
            $this->getConfig(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ADDRESS,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutAddressStepEnterPreCheckPlugins(),
            $this->getFlashMessenger(),
            $this->getTranslatorService()
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
