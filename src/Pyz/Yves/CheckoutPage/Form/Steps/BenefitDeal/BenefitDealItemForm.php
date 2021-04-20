<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * @method \Pyz\Yves\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class BenefitDealItemForm extends AbstractType
{
    public const FIELD_USE_BENEFIT = 'useBenefitVoucher';
    public const FIELD_USE_SHOPPING_POINTS = 'useShoppingPoints';
    public const FIELD_ITEMS_WITH_BENEFITS = 'amountItemsToUseBenefitVoucher';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $itemTransfer = $this->findItemTransfer($builder, $options);
        if ($itemTransfer->getShoppingPointsDeal() && $itemTransfer->getShoppingPointsDeal()->getIsActive()) {
            $this->addUseShoppingPointsSubForm($builder, $itemTransfer);
        } else {
            $this->addUseBenefitSubForm($builder, $itemTransfer);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return $this
     */
    private function addUseBenefitSubForm(FormBuilderInterface $builder, ItemTransfer $itemTransfer)
    {
        $builder->add(static::FIELD_USE_BENEFIT, CheckboxType::class, [
            'value' => (bool)$itemTransfer->getUseBenefitVoucher(),
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
                new EqualTo((int)$itemTransfer->getQuantity())
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return $this
     */
    private function addUseShoppingPointsSubForm(FormBuilderInterface $builder, ItemTransfer $itemTransfer)
    {
        $builder->add(static::FIELD_USE_SHOPPING_POINTS, CheckboxType::class, [
            'value' => (bool)$itemTransfer->getUseShoppingPoints(),
            'label' => ' ',
            'required' => false,
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
        //TODO: implement functionality for make available choice amount of items to pay with BV in one ItemTransfer
        $max = (int)$itemTransfer->getQuantity();

        return [
            $max => $max
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransfer(FormBuilderInterface $builder, array $options): ?ItemTransfer
    {
        $collection = $options[BenefitDealCollectionForm::OPTION_KEY_ITEMS];
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
            ->setRequired(BenefitDealCollectionForm::OPTION_KEY_ITEMS);
    }
}
