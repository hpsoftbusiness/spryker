<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Controller;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSessionAction(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFacade()->createPaymentSession($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomerAction(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFacade()->sendSmsCodeToCustomer($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCodeAction(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFacade()->validateSmsCode($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPaymentAction(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFacade()->confirmPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPaymentAction(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFacade()->getPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailableInternalPaymentPricesAction(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer
    {
        return $this->getFacade()->calculateAvailablePricesForInternalPayments($quoteTransfer);
    }
}
