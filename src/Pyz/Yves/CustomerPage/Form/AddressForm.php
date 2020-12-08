<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use Pyz\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\AddressForm as SprykerAddressForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class AddressForm extends SprykerAddressForm
{
    use AddressFormTrait;

    public const FIELD_ADDRESS_4 = 'address4';
    public const FIELD_VAT_NUMBER = 'vat_number';
    public const FIELD_STATE = 'state';

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
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addStateField($builder, $options)
            ->addPhoneField($builder)
            ->addCompanyField($builder)
            ->addAddress4Field($builder, $options)
            ->addVatNumberField($builder, $options)
            ->addIsDefaultShippingField($builder)
            ->addIsDefaultBillingField($builder)
            ->addIdCustomerAddressField($builder);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    public function createMaxLengthConstraint(array $options)
    {
        return new Length([
            'max' => CustomerPageConfig::MAX_LENGTH,
        ]);
    }
}
