<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiBusinessFactory;
use PyzTest\Zed\MyWorldPaymentApi\DataHelper;
use PyzTest\Zed\MyWorldPaymentApi\ExpiredTokenDataHelper;

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
    private const ERROR_CODE_MWS_IDENTITY_TOKEN_EXPIRED_OR_INVALID = 2;
    private const MAX_REPEAT_COUNT = 3;

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
        $this->markTestSkipped('Test has to be checked and fixed');
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
        $this->markTestSkipped('Test has to be checked and fixed');
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
        $this->markTestSkipped('Test has to be checked and fixed');
        $this->myWorldApiRequestTransfer
            ->getPaymentCodeValidateRequest()
            ->setSessionId(
                $this->sessionId
            )
            ->setConfirmationCode(1);

        $adapter = $this
            ->businessFactory
            ->createValidateSmsCodeAdapter(
                $this->myWorldApiRequestTransfer
            )
            ->allowUsingStubToken();
        $converter = $this->businessFactory->createValidateSmsCodeConverter();
        $mapper = $this->businessFactory->createValidateSmsCodeMapper();

        $myWorldApiResponseTransfer = $this
            ->businessFactory
            ->createMyWorldPaymentApiRequest(
                $adapter,
                $converter,
                $mapper
            )
            ->request(
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
        $this->markTestSkipped('Test has to be checked and fixed');
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
        $this->markTestSkipped('Test has to be checked and fixed');
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
     * @return void
     */
    public function testSendSessionRequestWithExpiredToken(): void
    {
        $transfer = (new ExpiredTokenDataHelper())->getMyWorldApiRequestTransfer();
        $adapter = $this
            ->businessFactory
            ->createPaymentSessionAdapter(
                $transfer
            )
            ->allowUsingStubToken();
        $converter = $this->businessFactory->createPaymentSessionConverter();
        $mapper = $this->businessFactory->createPaymentSessionMapper();

        $response = $this->businessFactory
            ->createMyWorldPaymentApiRequest(
                $adapter,
                $converter,
                $mapper
            )
            ->request($transfer);

        $this->assertSame(false, $response->getIsSuccess());
        $this->assertSame(self::ERROR_CODE_MWS_IDENTITY_TOKEN_EXPIRED_OR_INVALID, $response->getError()->getErrorCode());
    }

    /**
     * @param int $repeatCount
     *
     * @throws \Exception
     *
     * @return string|null
     */
    private function getSessionId($repeatCount = 1): ?string
    {
        $response = $this->sendSessionRequest();
        if ($response->getIsSuccess()) {
            return $response->getPaymentSessionResponse()->getSessionId();
        }
        if ($repeatCount <= self::MAX_REPEAT_COUNT) {
            $newReference = 'New_Reference__' . time();
            $this->myWorldApiRequestTransfer->getPaymentSessionRequest()->setReference($newReference);

            return $this->getSessionId(++$repeatCount);
        }

        throw new Exception("Session ID not fount. Exception message: " . $response->getError()->getErrorMessage());
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
