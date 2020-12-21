<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\DummyPrepayment\Plugin\StepEngine;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\DummyPrepayment\DummyPrepaymentFactory getFactory()
 */
class DummyPaymentStepHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createDummyPrepaymentStepHandler()
            ->addPaymentToQuote($request, $quoteTransfer);
    }
}
