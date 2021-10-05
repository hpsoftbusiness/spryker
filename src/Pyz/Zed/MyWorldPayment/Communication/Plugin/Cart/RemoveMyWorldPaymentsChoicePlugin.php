<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class RemoveMyWorldPaymentsChoicePlugin extends AbstractPlugin implements CartOperationPostSavePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->removeEVoucherPayment($quoteTransfer);
        $this->removeEVoucherOnBehalfOfMarketerPayment($quoteTransfer);
        $this->removeCashbackPayment($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    private function removeCashbackPayment(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->setUseCashbackBalance(null);
        $quoteTransfer->setTotalUsedCashbackBalanceAmount(null);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    private function removeEVoucherPayment(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->setUseEVoucherBalance(null);
        $quoteTransfer->setTotalUsedEVoucherBalanceAmount(null);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    private function removeEVoucherOnBehalfOfMarketerPayment(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(null);
        $quoteTransfer->setTotalUsedEVoucherMarketerBalanceAmount(null);
    }
}
