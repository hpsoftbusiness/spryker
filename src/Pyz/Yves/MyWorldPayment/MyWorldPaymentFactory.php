<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment;

use Pyz\Yves\MyWorldPayment\Form\BenefitVouchersForm;
use Pyz\Yves\MyWorldPayment\Form\BonusSubForm;
use Pyz\Yves\MyWorldPayment\Form\DataProvider\BenefitVouchersFormDataProvider;
use Pyz\Yves\MyWorldPayment\Form\DataProvider\BonusFormDataProvider;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

/**
 * @method \Pyz\Yves\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldPaymentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createBonusSubForm(): SubFormInterface
    {
        return new BonusSubForm();
    }

    /**
     * @return \Pyz\Yves\MyWorldPayment\Form\DataProvider\BonusFormDataProvider
     */
    public function createBonusSubFormDataProvider(): BonusFormDataProvider
    {
        return new BonusFormDataProvider();
    }
}
