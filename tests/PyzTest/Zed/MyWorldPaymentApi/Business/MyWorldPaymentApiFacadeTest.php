<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiBusinessFactory;
use PyzTest\Zed\MyWorldPaymentApi\DataHelper;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPaymentApi
 * @group Business
 * @group Facade
 * @group MyWorldPaymentApiFacadeTest
 *
 * Add your own group annotations below this line
 */
class MyWorldPaymentApiFacadeTest extends Unit
{
    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacade
     */
    private $businessFactory;

    /**
     * @var \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    private $myWorldApiRequestTransfer;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->businessFactory = new MyWorldPaymentApiBusinessFactory();
        $this->myWorldApiRequestTransfer = (new DataHelper())->getMyWorldApiRequestTransfer();
        $this->sessionId = $this->getSessionId();
    }

    /**
     * @return string|null
     */
    private function getSessionId(): ?string
    {
        $response = $this->sendSessionRequest();
        if ($response->getIsSuccess()) {
            return $response->getPaymentSessionResponse()->getSessionId();
        }

        return null;
    }

    /**
     * @return void
     */
    public function testCreatingPaymentSession()
    {
        $myWorldApiResponseTransfer = $this->sendSessionRequest();

        $this->assertTrue($myWorldApiResponseTransfer->getIsSuccess());
        $this->assertNotNull($myWorldApiResponseTransfer->getPaymentSessionResponse()->getSessionId());
    }

    /**
     * @return void
     */
    public function testGenerateSmsCode()
    {
        $this->myWorldApiRequestTransfer->getPaymentCodeGenerateRequest()->setSessionId($this->sessionId);

        $adapter = $this->businessFactory->createGenerateSmsCodeAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createGenerateSmsCodeConverter();
        $mapper = $this->businessFactory->createGenerateSmsCodeMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );

        $this->assertTrue($myWorldApiResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateSmsCode()
    {
        $this->myWorldApiRequestTransfer->getPaymentCodeValidateRequest()->setSessionId($this->sessionId);
        $adapter = $this->businessFactory->createValidateSmsCodeAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createValidateSmsCodeConverter();
        $mapper = $this->businessFactory->createValidateSmsCodeMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );

        $this->assertTrue($myWorldApiResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateNotValidSmsCode()
    {
        $this->myWorldApiRequestTransfer->getPaymentCodeValidateRequest()->setSessionId(
            $this->sessionId
        )->setConfirmationCode(1);
        $adapter = $this->businessFactory->createValidateSmsCodeAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createValidateSmsCodeConverter();
        $mapper = $this->businessFactory->createValidateSmsCodeMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );

        $this->assertFalse($myWorldApiResponseTransfer->getPaymentCodeValidateResponse()->getIsValid());
        $this->assertNotNull($myWorldApiResponseTransfer->getPaymentCodeValidateResponse()->getDescription());
    }

    /**
     * @return void
     */
    public function testConfirmPayment()
    {
        $this->myWorldApiRequestTransfer->getPaymentConfirmationRequest()->setSessionId($this->sessionId);
        $adapter = $this->businessFactory->createPaymentSessionApiCallAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createPaymentSessionApiCallConverter();
        $mapper = $this->businessFactory->createPaymentSessionApiCallMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );

        $this->assertTrue($myWorldApiResponseTransfer->getIsSuccess());
        $this->assertNotNull($myWorldApiResponseTransfer->getPaymentConfirmationResponseTransfer()->getPaymentId());
    }

    /**
     * @return void
     */
    public function testConfirmPaymentWithNotValidCode()
    {
        $this->myWorldApiRequestTransfer->getPaymentConfirmationRequest()->setSessionId(
            $this->sessionId
        )->setConfirmationCode(1);

        $adapter = $this->businessFactory->createPaymentSessionApiCallAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createPaymentSessionApiCallConverter();
        $mapper = $this->businessFactory->createPaymentSessionApiCallMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );
        $this->assertFalse($myWorldApiResponseTransfer->getIsSuccess());
        $this->assertNotNull($myWorldApiResponseTransfer->getError());
    }

    /**
     * @return void
     */
    public function testErrorCreateRefund()
    {
        $adapter = $this->businessFactory->createCreateRefundAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createCreateRefundConverter();
        $mapper = $this->businessFactory->createCreateRefundMapper();

        $myWorldApiResponseTransfer = $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );

        $this->assertNotNull($myWorldApiResponseTransfer->getError());
    }

    /**
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    private function sendSessionRequest(): MyWorldApiResponseTransfer
    {
        $adapter = $this->businessFactory->createPaymentSessionAdapter(
            $this->myWorldApiRequestTransfer
        )->allowUsingStubToken();
        $converter = $this->businessFactory->createPaymentSessionConverter();
        $mapper = $this->businessFactory->createPaymentSessionMapper();

        return $this->businessFactory->createMyWorldPaymentApiRequest(
            $adapter,
            $converter,
            $mapper
        )->request(
            $this->myWorldApiRequestTransfer
        );
    }
}