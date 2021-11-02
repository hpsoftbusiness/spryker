<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen;

use Pyz\Client\MyWorldPayment\MyWorldPaymentClient;
use Pyz\Yves\Adyen\Form\CreditCardSubForm;
use Pyz\Yves\Adyen\Form\Validation\CreditCardValidationGroupResolver;
use Pyz\Yves\Adyen\Handler\AdyenPaymentHandler;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Yves\Adyen\AdyenFactory as SprykerEcoAdyenFactory;
use SprykerEco\Yves\Adyen\Handler\AdyenPaymentHandlerInterface;

/**
 * @method \Pyz\Yves\Adyen\AdyenConfig getConfig()
 */
class AdyenFactory extends SprykerEcoAdyenFactory
{
    /**
     * @return \SprykerEco\Yves\Adyen\Handler\AdyenPaymentHandlerInterface
     */
    public function createAdyenPaymentHandler(): AdyenPaymentHandlerInterface
    {
        return new AdyenPaymentHandler(
            $this->getAdyenService(),
            $this->getAdyenPaymentPlugins()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardForm(): SubFormInterface
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Pyz\Yves\Adyen\Form\Validation\CreditCardValidationGroupResolver
     */
    public function createCreditCardValidationGroupResolver(): CreditCardValidationGroupResolver
    {
        return new CreditCardValidationGroupResolver(
            $this->getConfig(),
            $this->getMyWorldPaymentClient()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\MyWorldPaymentClient
     */
    public function getMyWorldPaymentClient(): MyWorldPaymentClient
    {
        return $this->getProvidedDependency(AdyenDependencyProvider::CLIENT_MYWORLD_PAYMENT);
    }
}
