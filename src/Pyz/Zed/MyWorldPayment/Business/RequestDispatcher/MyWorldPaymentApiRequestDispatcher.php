<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\RequestDispatcher;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface;

class MyWorldPaymentApiRequestDispatcher implements RequestDispatcherInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface
     */
    private $apiTransferGenerator;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface
     */
    private $myWorldPaymentApiFacade;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface
     */
    private $paymentApiLog;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface $apiTransferGenerator
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface $myWorldPaymentApiFacade
     * @param \Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface $paymentApiLog
     */
    public function __construct(
        MyWorldPaymentRequestApiTransferGeneratorInterface $apiTransferGenerator,
        MyWorldPaymentApiFacadeInterface $myWorldPaymentApiFacade,
        PaymentApiLogInterface $paymentApiLog
    ) {
        $this->apiTransferGenerator = $apiTransferGenerator;
        $this->myWorldPaymentApiFacade = $myWorldPaymentApiFacade;
        $this->paymentApiLog = $paymentApiLog;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchPaymentCreation(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        $requestTransfer = $this->apiTransferGenerator->createPerformPaymentSessionCreationRequest($quoteTransfer);
        $responseTransfer = $this->myWorldPaymentApiFacade->performCreatePaymentSessionApiCall($requestTransfer);

        $this->paymentApiLog->save($requestTransfer, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchSendSmsCodeToCustomer(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer
    {
        $responseTransfer = $this->myWorldPaymentApiFacade->performGenerateSmsCodeApiCall($apiRequestTransfer);

        $this->paymentApiLog->save($apiRequestTransfer, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchValidateSmsCode(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer
    {
        $responseTransfer = $this->myWorldPaymentApiFacade->performValidateSmsCodeApiCall($apiRequestTransfer);

        $this->paymentApiLog->save($apiRequestTransfer, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchConfirmPayment(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer
    {
        $responseTransfer = $this->myWorldPaymentApiFacade->performConfirmPaymentApiCall($apiRequestTransfer);

        $this->paymentApiLog->save($apiRequestTransfer, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchGetPayment(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer
    {
        $responseTransfer = $this->myWorldPaymentApiFacade->performGetPaymentApiCall($apiRequestTransfer);

        $this->paymentApiLog->save($apiRequestTransfer, $responseTransfer);

        return $responseTransfer;
    }
}
