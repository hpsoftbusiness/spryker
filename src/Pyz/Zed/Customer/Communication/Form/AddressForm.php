<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Form;

use Spryker\Zed\Customer\Communication\Form\AddressForm as SprykerAddressForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress4Field(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ADDRESS_4, TextType::class, [
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
}
