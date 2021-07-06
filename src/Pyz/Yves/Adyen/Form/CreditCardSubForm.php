<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen\Form;

use Pyz\Yves\Adyen\Form\Validation\CreditCardValidationGroupResolver;
use SprykerEco\Yves\Adyen\Form\CreditCardSubForm as SprykerCreditCardSubForm;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Pyz\Yves\Adyen\AdyenFactory getFactory()
 */
class CreditCardSubForm extends SprykerCreditCardSubForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            static::ENCRYPTED_CARD_NUMBER_FIELD,
            HiddenType::class,
            [
                'constraints' => [
                    $this->createNotBlankConstraint([
                        'message' => static::GLOSSARY_KEY_CONSTRAINT_MESSAGE_INVALID_CARD_NUMBER,
                        'groups' => [ Constraint::DEFAULT_GROUP ],
                    ]),
                ],
            ]
        );

        $builder->add(
            static::ENCRYPTED_EXPIRY_YEAR_FIELD,
            HiddenType::class,
            [
                'constraints' => [
                    $this->createNotBlankConstraint([
                        'message' => static::GLOSSARY_KEY_CONSTRAINT_MESSAGE_INVALID_EXPIRY_YEAR,
                        'groups' => [ Constraint::DEFAULT_GROUP ],
                    ]),
                ],
            ]
        );

        $builder->add(
            static::ENCRYPTED_EXPIRY_MONTH_FIELD,
            HiddenType::class,
            [
                'constraints' => [
                    $this->createNotBlankConstraint([
                        'message' => static::GLOSSARY_KEY_CONSTRAINT_MESSAGE_INVALID_EXPIRY_MONTH,
                        'groups' => [ Constraint::DEFAULT_GROUP ],
                    ]),
                ],
            ]
        );

        $builder->add(
            static::ENCRYPTED_SECURITY_CODE_FIELD,
            HiddenType::class,
            [
                'constraints' => [
                    $this->createNotBlankConstraint([
                        'message' => static::GLOSSARY_KEY_CONSTRAINT_MESSAGE_INVALID_SECURITY_CODE,
                        'groups' => [ Constraint::DEFAULT_GROUP ],
                    ]),
                ],
            ]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(['validation_groups' => $this->getValidationGroupResolver()]);
    }

    /**
     * @return \Pyz\Yves\Adyen\Form\Validation\CreditCardValidationGroupResolver
     */
    private function getValidationGroupResolver(): CreditCardValidationGroupResolver
    {
        return $this->getFactory()->createCreditCardValidationGroupResolver();
    }
}
