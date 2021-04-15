<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps;

use SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm as SpyPaymentForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 */
class PaymentForm extends SpyPaymentForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->addStaticPaymentMethods($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStaticPaymentMethods(FormBuilderInterface $builder, array $options)
    {
        $formExpanders = $this->getFormExpandersSubForms();

        $this->addFormExpanders($builder, $formExpanders, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface[] $paymentMethodSubForms
     * @param array $options
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm
     */
    protected function addFormExpanders(FormBuilderInterface $builder, array $paymentMethodSubForms, array $options)
    {
        foreach ($paymentMethodSubForms as $paymentMethodSubForm) {
            $paymentMethodSubForm->buildForm($builder, $options);
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getFormExpandersSubForms(): array
    {
        $paymentMethodSubForms = [];

        $paymentMethodPlugins = $this->getFactory()->getProvidedFormExpanders();

        foreach ($paymentMethodPlugins as $paymentMethodPluginCollectionPlugin) {
            $paymentMethodSubForms[] = $this->createSubForm($paymentMethodPluginCollectionPlugin);
        }

        return $paymentMethodSubForms;
    }
}
