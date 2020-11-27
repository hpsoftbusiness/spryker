<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupToProductListAssignmentTransfer;
use Spryker\Zed\CustomerGroup\Communication\Form\CustomerGroupForm as SprykerCustomerGroupForm;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerGroupForm extends SprykerCustomerGroupForm
{
    public const FIELD_PRODUCT_LIST_ASSIGNMENT = 'productListAssignment';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->addProductListAssignmentSubForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\CustomerGroup\Communication\Form\CustomerGroupForm
     */
    protected function addProductListAssignmentSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRODUCT_LIST_ASSIGNMENT,
            ProductListAssignmentForm::class,
            [
                'label' => false,
                'data_class' => CustomerGroupToProductListAssignmentTransfer::class,
            ]
        );

        return $this;
    }
}
