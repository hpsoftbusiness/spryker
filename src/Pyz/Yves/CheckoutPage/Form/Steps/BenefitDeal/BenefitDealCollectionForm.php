<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal;

use Pyz\Yves\CheckoutPage\Form\FormFactory;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageFactory getFactory()()
 */
class BenefitDealCollectionForm extends AbstractType
{
    public const FORM_FIELD_BENEFIT_ITEMS = 'benefitItems';
    public const OPTION_KEY_ITEMS = 'benefitItems';
    public const FORM_FIELD_USE_BENEFIT_VOUCHER = 'useBenefitVoucher';
    public const FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT = 'totalUsedBenefitVouchersAmount';

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
        $this->addFieldTotalUseBenefitVoucher($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        $resolver->setDefined(static::OPTION_KEY_ITEMS);

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
        $builder->add(static::FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT, NumberType::class, [
            'constraints' => [
                new PositiveOrZero(),
            ],
            'html5' => true,
            'scale' => 0,
        ]);

        $builder->get(static::FORM_FIELD_TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT)
            ->addModelTransformer($this->getBenefitVoucherAmountTransformer());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addFieldTotalUseBenefitVoucher(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FORM_FIELD_USE_BENEFIT_VOUCHER, CheckboxType::class, [
            'label' => '',
        ]);
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
