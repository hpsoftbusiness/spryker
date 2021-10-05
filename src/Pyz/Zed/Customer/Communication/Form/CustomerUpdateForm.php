<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Form;

use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm as SprykerCustomerUpdateForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerUpdateForm extends SprykerCustomerUpdateForm
{
    public const FIELD_MY_WORLD_CUSTOMER_ID = 'my_world_customer_id';
    public const FIELD_MY_WORLD_CUSTOMER_NUMBER = 'my_world_customer_number';
    public const FIELD_CARD_NUMBER = 'card_number';
    public const FIELD_CUSTOMER_TYPE = 'customer_type';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_COUNTRY_ID = 'country_id';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove(self::FIELD_SEND_PASSWORD_TOKEN);

        $this->addMyWorldCustomerIdField($builder);
        $this->addMyWorldCustomerNumberField($builder);
        $this->addCardNumberField($builder);
        $this->addCustomerTypeField($builder);
        $this->addCountryIdField($builder);
        $this->addIsActiveField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addMyWorldCustomerIdField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_MY_WORLD_CUSTOMER_ID, TextType::class, [
            'label' => 'MyWorld Customer ID',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addMyWorldCustomerNumberField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_MY_WORLD_CUSTOMER_NUMBER, TextType::class, [
            'label' => 'MyWorld Customer Number',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addCardNumberField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CARD_NUMBER, TextType::class, [
            'label' => 'Card Number',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addCustomerTypeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CUSTOMER_TYPE, TextType::class, [
            'label' => 'Customer Type',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addCountryIdField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COUNTRY_ID, TextType::class, [
            'label' => 'Country Id',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Is active',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_SALUTATION, ChoiceType::class, [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'required' => false,
            'choices' => array_flip($choices),
        ]);

        return $this;
    }
}
