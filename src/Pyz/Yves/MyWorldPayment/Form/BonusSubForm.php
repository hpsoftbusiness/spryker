<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment\Form;

use Pyz\Yves\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class BonusSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return MyWorldPaymentConfig::FORM_TEMPLATE_PATH;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return MyWorldPaymentConfig::FORM_PROPERTY_PATH;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return MyWorldPaymentConfig::FORM_NAME;
    }

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return MyWorldPaymentConfig::FORM_PROVIDER_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            MyWorldPaymentConfig::FORM_FIELD_BALANCE_NAME,
            CheckboxType::class,
            [
                'label' => ' ',
                'required' => false,
            ]
        );
    }
}
