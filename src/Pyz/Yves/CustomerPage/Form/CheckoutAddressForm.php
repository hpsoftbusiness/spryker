<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm as SprykerCheckoutAddressForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressForm extends SprykerCheckoutAddressForm
{
    /**
     * @uses \Pyz\Yves\CustomerPage\Form\AddressForm::FIELD_ADDRESS_4
     */
    public const FIELD_ADDRESS_4 = 'address4';

    /**
     * @uses \Pyz\Yves\CustomerPage\Form\AddressForm::FIELD_VAT_NUMBER
     */
    public const FIELD_VAT_NUMBER = 'vat_number';

    /**
     * @uses \Pyz\Yves\CustomerPage\Form\AddressForm::FIELD_STATE
     */
    public const FIELD_STATE = 'state';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addAddressSelectField($builder, $options)
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder)
            ->addAddress4Field($builder)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addStateField($builder)
            ->addPhoneField($builder)
            ->addIdCompanyUnitAddressTextField($builder)
            ->addCompanyField($builder)
            ->addVatNumberField($builder)
            ->addIsAddressSavingSkippedField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress4Field(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ADDRESS_4, TextType::class, [
            'label' => 'customer.address.address4',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVatNumberField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VAT_NUMBER, TextType::class, [
            'label' => 'customer.address.vat_number',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStateField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_STATE, TextType::class, [
            'label' => 'customer.address.state',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }
}
