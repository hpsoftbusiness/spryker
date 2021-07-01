<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class BenefitDealItemForm extends AbstractType
{
    public const FIELD_USE_BENEFIT = 'useBenefitVoucher';
    public const FIELD_USE_SHOPPING_POINTS = 'useShoppingPoints';
    public const FIELD_OTHER_ITEM = 'otherItem';
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
        if ($itemTransfer->getShoppingPointsDeal()
            && $itemTransfer->getShoppingPointsDeal()->getIsActive()
        ) {
            $this->addUseShoppingPointsSubForm($builder, $itemTransfer);
        } elseif ($itemTransfer->getBenefitVoucherDealData()
            && $itemTransfer->getBenefitVoucherDealData()->getAmount() > 0
        ) {
            $this->addUseBenefitSubForm($builder, $itemTransfer);
        } else {
            $this->addOtherItemSubForm($builder);
        }
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
            'attr' => [
                'class' => 'benefit_deal_use_shopping_points',
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
    private function addUseBenefitSubForm(FormBuilderInterface $builder, ItemTransfer $itemTransfer)
    {
        $builder->add(static::FIELD_USE_BENEFIT, HiddenType::class, [
            'data' => (bool)$itemTransfer->getUseBenefitVoucher(),
            'label' => ' ',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    private function addOtherItemSubForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_OTHER_ITEM, HiddenType::class, [
            'data' => false,
            'label' => ' ',
        ]);

        return $this;
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
