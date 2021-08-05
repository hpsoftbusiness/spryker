<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Form\TableFilter;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class StatusTableFilterForm extends AbstractType
{
    public const BLOCK_PREFIX = '';
    public const FIELD_DEACTIVATED = 'deactivated';
    public const FIELD_ACTIVE = 'active';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDeactivatedField($builder)
            ->addActiveField($builder);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeactivatedField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DEACTIVATED, CheckboxType::class, [
            'label' => 'Deactivated',
            'required' => false,
            'label_attr' => ['class' => 'label label-danger'],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
            'required' => false,
            'label_attr' => ['class' => 'label label-info'],
        ]);

        return $this;
    }
}
