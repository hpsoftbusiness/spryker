<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\AddressForm as SprykerAddressForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class AddressForm extends SprykerAddressForm
{
    public const FIELD_ADDRESS_4 = 'address4';
    public const FIELD_VAT_NUMBER = 'vat_number';
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
            ->addCompanyField($builder)
            ->addVatNumberField($builder)
            ->addIdCustomerAddressField($builder);
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
