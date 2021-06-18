<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class EVoucherDirectPaymentTransferGenerator extends AbstractDirectPaymentTransferGenerator
{
    protected const PAYMENT_METHOD_NAME = MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getUseEVoucherBalance() && $quoteTransfer->getTotalUsedEVoucherBalanceAmount() > 0;
    }

    /**
     * @return int
     */
    protected function getPaymentOptionId(): int
    {
        return $this->config->getOptionEVoucher();
    }
}
