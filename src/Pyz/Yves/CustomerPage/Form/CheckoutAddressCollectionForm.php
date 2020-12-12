<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm as SprykerCheckoutAddressCollectionForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressCollectionForm extends SprykerCheckoutAddressCollectionForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm
     */
    protected function addShippingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        parent::addShippingAddressSubForm($builder, $options);

        $options = $builder->get(static::FIELD_SHIPPING_ADDRESS)->getOptions();
        $builder->add(static::FIELD_SHIPPING_ADDRESS, CheckoutAddressForm::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addBillingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        parent::addBillingAddressSubForm($builder, $options);

        $options = $builder->get(static::FIELD_BILLING_ADDRESS)->getOptions();
        $builder->add(static::FIELD_BILLING_ADDRESS, CheckoutAddressForm::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCustomerAddressExistAndNotEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            && (int)$form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() !== (int)CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCustomerAddressFieldNotEmpty(FormInterface $form): bool
    {
        return !$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            || (int)$form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() !== (int)CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS;
    }
}
