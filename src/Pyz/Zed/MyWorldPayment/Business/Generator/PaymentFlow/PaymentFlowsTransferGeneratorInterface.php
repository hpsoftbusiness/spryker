<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow;

use Generated\Shared\Transfer\FlowsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentFlowsTransferGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FlowsTransfer
     */
    public function generate(QuoteTransfer $quoteTransfer): FlowsTransfer;
}
