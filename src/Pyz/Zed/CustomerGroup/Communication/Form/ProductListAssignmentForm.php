<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Form;

use Generated\Shared\Transfer\CustomerGroupToProductListAssignmentTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class ProductListAssignmentForm extends AbstractType
{
    public const FIELD_ID_CUSTOMER_GROUP = 'idCustomerGroup';
    public const FIELD_IDS_PRODUCT_LIST_TO_ASSIGN_CSV = 'idsProductListToAssign';
    public const FIELD_IDS_PRODUCT_LIST_TO_DE_ASSIGN_CSV = 'idsProductListToDeAssign';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CustomerGroupToProductListAssignmentTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdCustomerGroupField($builder)
            ->addIdsProductListToAssignField($builder)
            ->addIdsProductListToDeAssignField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerGroupField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_CUSTOMER_GROUP,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsProductListToAssignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_PRODUCT_LIST_TO_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-product-list-to-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_PRODUCT_LIST_TO_ASSIGN_CSV, $builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdsProductListToDeAssignField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IDS_PRODUCT_LIST_TO_DE_ASSIGN_CSV,
            HiddenType::class,
            [
                'attr' => [
                    'id' => 'js-product-list-to-de-assign-ids-csv-field',
                ],
            ]
        );

        $this->addIdsCsvModelTransformer(static::FIELD_IDS_PRODUCT_LIST_TO_DE_ASSIGN_CSV, $builder);

        return $this;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIdsCsvModelTransformer($fieldName, FormBuilderInterface $builder): void
    {
        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function (array $idsAsArray) {
                    if (!count($idsAsArray)) {
                        return [];
                    }

                    return implode(',', $idsAsArray);
                },
                function ($idsAsString) {
                    if (empty($idsAsString)) {
                        return [];
                    }

                    return explode(',', $idsAsString);
                }
            ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'productListAssignment';
    }
}
