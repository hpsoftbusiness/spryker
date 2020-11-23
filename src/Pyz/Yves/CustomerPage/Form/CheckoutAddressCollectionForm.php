<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm as SprykerCheckoutAddressCollectionForm;
use Symfony\Component\Form\FormBuilderInterface;

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
}
