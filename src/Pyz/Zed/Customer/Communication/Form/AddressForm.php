<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Form;

use Spryker\Zed\Customer\Communication\Form\AddressForm as SprykerAddressForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addAddress4Field($builder);
        $this->addStateField($builder);
        $this->addVatNumberField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => true,
            static::OPTION_PREFERRED_COUNTRY_CHOICES => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addAddress2Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_2, TextType::class, [
            'label' => 'Address line 2',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
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
            'label' => 'Address line 4',
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
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PHONE, TextType::class, [
            'label' => 'Phone',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_SALUTATION, ChoiceType::class, [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'required' => false,
            'choices' => array_flip($choices),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMMENT, TextareaType::class, [
            'label' => 'Comment',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMPANY, TextType::class, [
            'label' => 'Company',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addAddress3Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_3, TextType::class, [
            'label' => 'Address line 3',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }
}
