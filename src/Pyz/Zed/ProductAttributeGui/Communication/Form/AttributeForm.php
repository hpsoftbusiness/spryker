<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm as SprykerAttributeForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributeForm extends SprykerAttributeForm
{
    public const FIELD_SHOW_ON_PDP = 'show_on_pdp';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdProductManagementAttribute($builder)
            ->addKeyField($builder, $options)
            ->addInputTypeField($builder, $options)
            ->addIsSuperField($builder, $options)
            ->addValuesField($builder, $options)
            ->addAllowInputField($builder, $options)
            ->addShowOnPdpField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShowOnPdpField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SHOW_ON_PDP, CheckboxType::class, [
            'label' => 'Show Attribute on Product Detail Page (PDP)',
        ]);

        return $this;
    }
}
