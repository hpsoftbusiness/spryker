<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutPage\Form\Steps\SummaryForm as SprykerSummaryForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SummaryForm extends SprykerSummaryForm
{
    public const OPTION_SMS_CODE = 'OPTION_SMS_CODE';

    protected const FIELD_SMS_CODE = QuoteTransfer::SMS_CODE;

    protected const LENGTH_ERROR_MESSAGE = 'You need to enter exactly 6 characters';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (isset($options[self::OPTION_SMS_CODE])) {
            $this->addSmsCodeField($builder, $options);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL);
        $resolver->setDefined(static::OPTION_SMS_CODE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\Steps\SummaryForm
     */
    protected function addSmsCodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SMS_CODE, TextType::class, [
            'label' => $options[static::OPTION_SMS_CODE],
            'attr' => [
                'maxlength' => 6,
            ],
            'required' => true,
            'constraints' => [
                new NotBlank(
                    [
                        'message' => static::VALIDATION_NOT_BLANK_MESSAGE,
                    ]
                ),
            ],
        ]);

        return $this;
    }
}
