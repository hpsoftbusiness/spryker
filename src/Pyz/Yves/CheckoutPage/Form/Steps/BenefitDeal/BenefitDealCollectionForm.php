<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal;

use Pyz\Yves\CheckoutPage\Form\FormFactory;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageFactory getFactory()()
 */
class BenefitDealCollectionForm extends AbstractType
{
    public const FORM_FIELD_BENEFIT_ITEMS = 'benefitItems';
    public const OPTION_KEY_ITEMS = 'benefitItems';
    public const FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT = 'totalUsedBenefitVouchersAmount';
    public const OPTION_KEY_CUSTOMER_BALANCE = 'customerBalance';
    public const OPTION_KEY_QUOTE = 'quote';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCollectionsForUseBenefitVouchers($builder, $options);
        $this->addFieldTotalUsedBenefitVouchersAmount($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        $resolver->setDefined(static::OPTION_KEY_ITEMS);
        $resolver->setDefined(static::OPTION_KEY_QUOTE);
        $resolver->setDefined(static::OPTION_KEY_CUSTOMER_BALANCE);

        return $resolver;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addCollectionsForUseBenefitVouchers(FormBuilderInterface $builder, array $options): void
    {
        $listItems = $options[static::OPTION_KEY_ITEMS];

        $builder->add(static::FORM_FIELD_BENEFIT_ITEMS, CollectionType::class, [
            'entry_type' => BenefitDealItemForm::class,
            'data' => $listItems->getArrayCopy(),
            'mapped' => false,
            'entry_options' => [
                static::OPTION_KEY_ITEMS => $listItems,
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addFieldTotalUsedBenefitVouchersAmount(FormBuilderInterface $builder, array $options): void
    {
        $defaultBenefitAmount = $this->getDefaultBenefitAmount($options);
        $customerAmount = $this->getCustomerAvailableBenefitVoucherAmount($options);

        $builder->add(static::FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT, NumberType::class, [
            'constraints' => [
                new PositiveOrZero(),
                new LessThanOrEqual($customerAmount),
            ],
            'html5' => true,
            'scale' => 2,
            'data' => $defaultBenefitAmount,
        ]);

        $builder->get(static::FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT)
            ->addModelTransformer($this->getBenefitVoucherAmountTransformer());
    }

    /**
     * @param array $options
     *
     * @return int
     */
    protected function getDefaultBenefitAmount(array $options): int
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quote */
        $quote = $options[static::OPTION_KEY_QUOTE];
        $currentAmount = $quote->getTotalUsedBenefitVouchersAmount();
        if ($currentAmount > 0) {
            return $currentAmount;
        }

        $customerAmount = $this->getCustomerAvailableBenefitVoucherAmount($options);
        $itemsAmount = $this->getSumBenefitAmount($options);
        $default = $customerAmount;
        if ($customerAmount > $itemsAmount) {
            $default = $itemsAmount;
        }

        return $default;
    }

    /**
     * @param array $options
     *
     * @return int
     */
    protected function getCustomerAvailableBenefitVoucherAmount(array $options): int
    {
        /** @var \Generated\Shared\Transfer\CustomerBalanceTransfer $customerBalance */
        $customerBalance = $options[static::OPTION_KEY_CUSTOMER_BALANCE];

        return (int)floor($customerBalance->getAvailableBenefitVoucherAmount()->toFloat() * 100);
    }

    /**
     * @param array $options
     *
     * @return int
     */
    protected function getSumBenefitAmount(array $options): int
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer[] $items */
        $items = $options[static::OPTION_KEY_ITEMS];
        $sumBenefitAmount = 0;
        foreach ($items as $item) {
            $benefitVoucherDeal = $item->getBenefitVoucherDealData();
            if ($benefitVoucherDeal === null) {
                continue;
            }
            $itemBenefitAmount = $benefitVoucherDeal->getAmount() * $item->getQuantity();
            $sumBenefitAmount = $sumBenefitAmount + $itemBenefitAmount;
        }

        return $sumBenefitAmount;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    private function getBenefitVoucherAmountTransformer(): DataTransformerInterface
    {
        return $this->getFormFactory()->createBenefitVoucherAmountTransformer();
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\FormFactory
     */
    private function getFormFactory(): FormFactory
    {
        return $this->getFactory()->createCheckoutFormFactory();
    }
}
