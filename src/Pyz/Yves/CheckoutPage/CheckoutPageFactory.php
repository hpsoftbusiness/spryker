<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Pyz\Client\Messenger\MessengerClientInterface;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClient;
use Pyz\Yves\CheckoutPage\Form\FormFactory;
use Pyz\Yves\CheckoutPage\Form\Validation\PaymentSelectionValidationGroupResolver;
use Pyz\Yves\CheckoutPage\Process\StepFactory;
use SprykerShop\Yves\CheckoutPage\CheckoutPageFactory as SprykerShopCheckoutPageFactory;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class CheckoutPageFactory extends SprykerShopCheckoutPageFactory
{
    /**
     * @return \Pyz\Yves\CheckoutPage\Process\StepFactory
     */
    public function createStepFactory()
    {
        return new StepFactory();
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\FormFactory
     */
    public function createCheckoutFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \Pyz\Client\Messenger\MessengerClientInterface
     */
    public function getMessengerClient(): MessengerClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\Validation\PaymentSelectionValidationGroupResolver
     */
    public function createPaymentSelectionValidationGroupResolver(): PaymentSelectionValidationGroupResolver
    {
        return new PaymentSelectionValidationGroupResolver(
            $this->getMyWorldPaymentClient(),
            $this->getMessengerClient()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\MyWorldPaymentClient
     */
    public function getMyWorldPaymentClient(): MyWorldPaymentClient
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::MY_WORLD_PAYMENT_CLIENT);
    }
}
