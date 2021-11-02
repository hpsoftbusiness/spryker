<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\MyWorldPayment\MyWorldPaymentFactory getFactory()
 */
class MyWorldPaymentClient extends AbstractClient implements MyWorldPaymentClientInterface
{
    /**
     * @return \Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub
     */
    protected function getZedStub(): MyWorldPaymentStub
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSession(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        return $this->getZedStub()->createPaymentSession($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomerByQuote(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        return $this->getZedStub()->sendSmsCodeToCustomerByQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCode(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getZedStub()->validateSmsCode($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getZedStub()->confirmPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getZedStub()->getPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailableInternalPaymentPrices(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer
    {
        return $this->getZedStub()->getAvailableInternalPaymentPrices($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $paymentOptionId
     *
     * @return bool
     */
    public function assertInternalPaymentCoversPriceToPay(QuoteTransfer $quoteTransfer, int $paymentOptionId): bool
    {
        return $this->getFactory()->createMyWorldPaymentReader()->assertInternalPaymentCoversPriceToPay(
            $quoteTransfer,
            $paymentOptionId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    public function findUsedInternalPaymentMethodOptionId(QuoteTransfer $quoteTransfer): ?int
    {
        return $this->getFactory()->createMyWorldPaymentReader()->findUsedInternalPaymentMethodOptionId($quoteTransfer);
    }
}
