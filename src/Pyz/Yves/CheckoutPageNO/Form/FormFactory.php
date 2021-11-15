<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPageNO\Form;

use Pyz\Yves\CheckoutPage\Form\FormFactory as PyzFormFactory;
use Pyz\Yves\CheckoutPageNO\Form\Steps\PaymentForm;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class FormFactory extends PyzFormFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function getPaymentFormCollection(): FormCollectionHandlerInterface
    {
        $formDataProvider = $this->getConfig()->isCashbackFeatureEnabled()
            ? $this->createPaymentFormDataProvider()
            : $this->createSubFormDataProvider($this->getPaymentMethodSubForms());

        return $this->createSubFormCollection(PaymentForm::class, $formDataProvider);
    }
}
