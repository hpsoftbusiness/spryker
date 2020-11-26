<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm as SprykerCheckoutAddressForm;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
     * @uses \Pyz\Yves\CustomerPage\Form\AddressForm::PLACEHOLDER_PHONE
     */
    public const PLACEHOLDER_PHONE = '+00999999999';

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
            ->addSalutationField($builder, $options)
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addCompanyField($builder)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addPhoneField($builder, $options)
            ->addIdCompanyUnitAddressTextField($builder)
            ->addIsAddressSavingSkippedField($builder, $options)
            ->addStateField($builder)
            ->addAddress4Field($builder)
            ->addVatNumberField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerShop\Yves\CustomerPage\Form\AddressForm
     */
    protected function addPhoneField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_PHONE, TelType::class, [
            'label' => 'customer.address.phone',
            'attr' => [
                'placeholder' => static::PLACEHOLDER_PHONE,
            ],
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
            ],
        ]);

        return $this;
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
