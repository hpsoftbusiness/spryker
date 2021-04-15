<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Pyz\Yves\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class BenefitDealCollectionForm extends AbstractType
{
    public const FORM_FIELD_BENEFIT_ITEMS = 'benefitItems';

    public const OPTION_KEY_ITEMS = 'benefitItems';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCollectionsForUseBenefitVouchers($builder, $options);
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
}
