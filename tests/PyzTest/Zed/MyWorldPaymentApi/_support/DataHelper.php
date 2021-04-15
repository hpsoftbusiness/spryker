<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi;

use ArrayObject;
use Generated\Shared\DataBuilder\FlowsBuilder;
use Generated\Shared\DataBuilder\MwsDirectPaymentOptionBuilder;
use Generated\Shared\DataBuilder\PartialRefundBuilder;
use Generated\Shared\DataBuilder\PaymentCodeGenerateRequestBuilder;
use Generated\Shared\DataBuilder\PaymentCodeValidateRequestBuilder;
use Generated\Shared\DataBuilder\PaymentConfirmationRequestBuilder;
use Generated\Shared\DataBuilder\PaymentDataRequestBuilder;
use Generated\Shared\DataBuilder\PaymentRefundRequestBuilder;
use Generated\Shared\DataBuilder\PaymentSessionRequestBuilder;
use Generated\Shared\DataBuilder\SsoAccessTokenBuilder;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer;
use Generated\Shared\Transfer\PaymentConfirmationRequestTransfer;
use Generated\Shared\Transfer\PaymentDataRequestTransfer;
use Generated\Shared\Transfer\PaymentRefundRequestTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;

class DataHelper
{
    /**
     * @var \PyzTest\Zed\MyWorldPaymentApi\StubTokenClient
     */
    private $stubClient;
    
    public function __construct()
    {
        $this->stubClient = new StubTokenClient();
    }

    /**
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function getMyWorldApiRequestTransfer(): MyWorldApiRequestTransfer
    {
        $myWorldApiRequestTransfer = new MyWorldApiRequestTransfer();
        $myWorldApiRequestTransfer->setPaymentSessionRequest($this->getPaymentSessionRequest());
        $myWorldApiRequestTransfer->setPaymentCodeGenerateRequest($this->getPaymentCodeGenerateRequestTransfer());
        $myWorldApiRequestTransfer->setPaymentCodeValidateRequest($this->getPaymentCodeValidateRequestTransfer());
        $myWorldApiRequestTransfer->setPaymentConfirmationRequest($this->getPaymentConfirmationRequestTransfer());
        $myWorldApiRequestTransfer->setPaymentDataRequest($this->getPaymentDataRequestTransfer());
        $myWorldApiRequestTransfer->setPaymentRefundRequest($this->getPaymentRefundRequestTransfer());

        return $myWorldApiRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentSessionRequestTransfer
     */
    private function getPaymentSessionRequest(): PaymentSessionRequestTransfer
    {
        $ssoAccessToken = $this->getSsoAccessToken();
        $paymentSessionRequestTransfer = (new PaymentSessionRequestBuilder(
            [
                'PaymentOptions' => [1, 9, 8],
                'Amount' => 10,
                'CurrencyId' => 'USD',
            ]
        ))->build();

        $flow = (new FlowsBuilder(['type' => 4]))->build();
        $MwsDirect1 = (new MwsDirectPaymentOptionBuilder(
            [
                'PaymentOptionId' => 1,
                'Amount' => 5,
                'Unit' => 'USD',
                'UnitType' => 'Currency',
            ]
        ))->build();

        $MwsDirect2 = (new MwsDirectPaymentOptionBuilder(
            [
                'PaymentOptionId' => 9,
                'Amount' => 5,
                'Unit' => 'ShoppingPoints',
                'UnitType' => 'Unit',
            ]
        ))->build();

        $MwsDirect3 = (new MwsDirectPaymentOptionBuilder(
            [
                'PaymentOptionId' => 8,
                'Amount' => 5,
                'Unit' => 'USD',
                'UnitType' => 'Currency',
            ]
        ))->build();

        $flow->setMwsDirect(new ArrayObject([$MwsDirect1, $MwsDirect2, $MwsDirect3]));

        $paymentSessionRequestTransfer->setFlows($flow);
        $paymentSessionRequestTransfer->setSsoAccessToken($ssoAccessToken);

        return $paymentSessionRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer
     */
    private function getPaymentCodeGenerateRequestTransfer(): PaymentCodeGenerateRequestTransfer
    {
        return (new PaymentCodeGenerateRequestBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer
     */
    private function getPaymentCodeValidateRequestTransfer(): PaymentCodeValidateRequestTransfer
    {
        return (new PaymentCodeValidateRequestBuilder(['confirmationCode' => 999999]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    private function getSsoAccessToken(): SsoAccessTokenTransfer
    {
        $token = $this->stubClient->getStubToken();

        return (new SsoAccessTokenBuilder(
            [
                'accessToken' => $token,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentConfirmationRequestTransfer
     */
    private function getPaymentConfirmationRequestTransfer(): PaymentConfirmationRequestTransfer
    {
        return (new PaymentConfirmationRequestBuilder(['confirmationCode' => 999999]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentDataRequestTransfer
     */
    private function getPaymentDataRequestTransfer(): PaymentDataRequestTransfer
    {
        return (new PaymentDataRequestBuilder(['paymentId' => '799ADE3F-9B78-E911-80C6-AC162D7C8193']))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentRefundRequestTransfer
     */
    private function getPaymentRefundRequestTransfer(): PaymentRefundRequestTransfer
    {
        $paymentRefundRequestTransfer = (new PaymentRefundRequestBuilder(
            ['paymentId' => '799ADE3F-9B78-E911-80C6-AC162D7C8193', 'amount' => 1, 'currencyId' => 'EUR']
        ))->build();
        $partialRefunds = new ArrayObject(
            [
                (new PartialRefundBuilder(
                    ['paymentOptionId' => 1, 'amount' => 1, 'unit' => 'EUR', 'UnitType' => 'Currency']
                ))->build(),
            ]
        );
        $paymentRefundRequestTransfer->setPartialRefunds($partialRefunds);

        return $paymentRefundRequestTransfer;
    }
}
