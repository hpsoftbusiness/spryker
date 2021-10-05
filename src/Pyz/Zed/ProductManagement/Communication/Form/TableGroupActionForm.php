<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Form;

use Pyz\Shared\ProductManagement\ProductManagementConstants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class TableGroupActionForm extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('/product-management/index/group-function');

        $this->addActivateField($builder)
            ->addDeactivateField($builder)
            ->addDeleteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActivateField(FormBuilderInterface $builder)
    {
        $builder->add(ProductManagementConstants::GROUP_ACTION_ACTIVATE, CheckboxType::class, [
            'label' => 'activate',
            'required' => false,
            'label_attr' => ['style' => 'color: green'],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeactivateField(FormBuilderInterface $builder)
    {
        $builder->add(ProductManagementConstants::GROUP_ACTION_DEACTIVATE, CheckboxType::class, [
            'label' => 'deactivate',
            'required' => false,
            'label_attr' => ['style' => 'color: red'],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeleteField(FormBuilderInterface $builder)
    {
        $builder->add(ProductManagementConstants::GROUP_ACTION_DELETE, CheckboxType::class, [
            'label' => 'delete',
            'required' => false,
            'label_attr' => ['style' => 'color: red'],
        ]);

        return $this;
    }
}
