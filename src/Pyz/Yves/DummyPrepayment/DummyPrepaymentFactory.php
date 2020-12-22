<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\DummyPrepayment;

use Pyz\Yves\DummyPrepayment\Form\DataProvider\DummyPrepaymentFormDataProvider;
use Pyz\Yves\DummyPrepayment\Form\DummyPrepaymentSubForm;
use Pyz\Yves\DummyPrepayment\Handler\DummyPrepaymentStepHandler;
use Pyz\Yves\DummyPrepayment\Handler\DummyPrepaymentStepHandlerInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

class DummyPrepaymentFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Yves\DummyPrepayment\Handler\DummyPrepaymentStepHandlerInterface
     */
    public function createDummyPrepaymentStepHandler(): DummyPrepaymentStepHandlerInterface
    {
        return new DummyPrepaymentStepHandler();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createDummyPrepaymentSubForm(): SubFormInterface
    {
        return new DummyPrepaymentSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDummyPrepaymentFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new DummyPrepaymentFormDataProvider();
    }
}
