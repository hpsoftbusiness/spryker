<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitVoucher;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Pyz\Yves\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class BenefitVoucherItemForm extends AbstractType
{
    public const FIELD_USE_BENEFIT = 'useBenefitVoucher';
    public const FIELD_ITEMS_WITH_BENEFITS = 'amountItemsToUseBenefitVoucher';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addUseBenefitSubForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addUseBenefitSubForm(FormBuilderInterface $builder, array $options)
    {
        $itemTransfer = $this->findItemTransfer($builder, $options);

        $builder->add(static::FIELD_USE_BENEFIT, CheckboxType::class, [
            'value' => $itemTransfer->getUseBenefitVoucher() === null ? false : true,
            'label' => ' ',
            'required' => false,
        ]);

        $builder->add(static::FIELD_ITEMS_WITH_BENEFITS, ChoiceType::class, [
            'data' => $itemTransfer->getAmountItemsToUseBenefitVoucher(),
            'choices' => $this->createArrayChoices($itemTransfer),
            'label' => false,
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
            'constraints' => [
                new Range(['max' => (int)$itemTransfer->getQuantity(), 'min' => 1]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function createArrayChoices(ItemTransfer $itemTransfer): array
    {
        $max = (int)$itemTransfer->getQuantity();
        $result = [];

        for ($index = 1; $index <= $max; $index++) {
            $result[$index] = $index;
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransfer(FormBuilderInterface $builder, array $options): ?ItemTransfer
    {
        $collection = $options[BenefitVoucherCollectionForm::OPTION_KEY_ITEMS];
        $index = $this->findItemTransferIndex($builder);

        if (!isset($collection[$index])) {
            return null;
        }

        return $collection[$index];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return string|null
     */
    protected function findItemTransferIndex(FormBuilderInterface $builder): ?string
    {
        $propertyPath = $builder->getPropertyPath();
        if ($propertyPath === null) {
            return null;
        }

        return $propertyPath->getElement(0);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => ItemTransfer::class,
            ])
            ->setRequired(BenefitVoucherCollectionForm::OPTION_KEY_ITEMS);
    }
}
