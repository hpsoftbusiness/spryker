<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Form;

use Pyz\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm as SprykerCheckoutAddressForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressForm extends SprykerCheckoutAddressForm
{
    use AddressFormTrait;

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
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder, $options)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addStateField($builder, $options)
            ->addPhoneField($builder, $options)
            ->addCompanyField($builder, $options)
            ->addAddress4Field($builder, $options)
            ->addVatNumberField($builder, $options)
            ->addIdCompanyUnitAddressTextField($builder)
            ->addIsAddressSavingSkippedField($builder, $options);
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
            'groups' => static::getValidationGroup($options),
        ]);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    public function createPhoneNumberConstraint(array $options)
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/m',
            'message' => 'validator.check.phone',
            'groups' => $validationGroup,
        ]);
    }
}
