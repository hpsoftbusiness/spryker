<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\DummyPrepayment\Form;

use Generated\Shared\Transfer\DummyPaymentTransfer;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DummyPrepaymentSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return DummyPrepaymentConfig::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return DummyPrepaymentConfig::DUMMY_PREPAYMENT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return DummyPrepaymentConfig::DUMMY_PREPAYMENT;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return DummyPrepaymentConfig::PROVIDER_NAME . DIRECTORY_SEPARATOR . 'prepayment';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DummyPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }
}
