<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment\Zed;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class MyWorldPaymentStub extends ZedRequestStub implements MyWorldPaymentStubInterface
{
    /**
     * @var \Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface
     */
    private $quoteResponseTransferMapper;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedStub
     * @param \Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface $quoteResponseTransferMapper
     */
    public function __construct(ZedRequestClientInterface $zedStub, QuoteResponseTransferMapperInterface $quoteResponseTransferMapper)
    {
        parent::__construct($zedStub);
        $this->quoteResponseTransferMapper = $quoteResponseTransferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSession(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        return $this->quoteResponseTransferMapper->transferResponseToMyWorldApiResponseTransfer(
            $this->zedStub->call('/my-world-payment/gateway/create-payment-session', $quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomer(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return (new MyWorldApiResponseTransfer())
            ->fromArray(
                $this->zedStub->call('/my-world-payment/gateway/send-sms-code-to-customer', $myWorldApiRequestTransfer)->toArray()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCode(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return (new MyWorldApiResponseTransfer())
            ->fromArray(
                $this->zedStub->call('/my-world-payment/gateway/validate-sms-code', $myWorldApiRequestTransfer)->toArray()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return (new MyWorldApiResponseTransfer())
            ->fromArray(
                $this->zedStub->call('/my-world-payment/gateway/confirm-payment', $myWorldApiRequestTransfer)->toArray()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return (new MyWorldApiResponseTransfer())
            ->fromArray(
                $this->zedStub->call('/my-world-payment/gateway/get-payment', $myWorldApiRequestTransfer)->toArray()
            );
    }
}
