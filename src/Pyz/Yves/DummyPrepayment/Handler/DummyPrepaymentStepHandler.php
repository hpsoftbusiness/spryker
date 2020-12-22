<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\DummyPrepayment\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Symfony\Component\HttpFoundation\Request;

class DummyPrepaymentStepHandler implements DummyPrepaymentStepHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->getPayment()
            ->setPaymentProvider(DummyPrepaymentConfig::DUMMY_PREPAYMENT)
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentSelection());

        return $quoteTransfer;
    }
}
