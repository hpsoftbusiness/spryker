<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Service\MyWorldPaymentApi;

use Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer;
use PHPUnit\Framework\TestCase;
use Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiService;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Service
 * @group MyWorldPaymentApi
 * @group MyWorldPaymentApiServiceTest
 * Add your own group annotations below this line
 */
class MyWorldPaymentApiServiceTest extends TestCase
{
    private const EXAMPLE_AUTH_HEADER = 'MWS HMACv1:52452145886147:Cep+Tx5hmI8tebPBzct5TPL7oUEeRGR05wiVtp2jGgs=';
    /**
     * @var \Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiService
     */
    protected $myWorldPaymentApiService;

    /**
     * @var \Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer
     */
    protected $authorizationHeaderRequestTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->myWorldPaymentApiService = new MyWorldPaymentApiService();
        $this->authorizationHeaderRequestTransfer = new AuthorizationHeaderRequestTransfer();

        $this->authorizationHeaderRequestTransfer->setSecretApiKey('IQsE8JO(uHVVXhnb0XxLsi0onX}i3kA}1>DFEB]7');
        $this->authorizationHeaderRequestTransfer->setApiKeyId('52452145886147');
        $this->authorizationHeaderRequestTransfer->setHttpMethod('GET');
        $this->authorizationHeaderRequestTransfer->setUri('https://preprod-payments-api.myworldwebservices.com/payments/123456');
    }

    /**
     * @return void
     */
    public function testGetAuthorizationHeader()
    {
        $authHeader = $this->myWorldPaymentApiService->getAuthorizationHeader(
            $this->authorizationHeaderRequestTransfer
        );
        $this->assertEquals(strlen(self::EXAMPLE_AUTH_HEADER), strlen($authHeader));
        $this->assertContains($this->authorizationHeaderRequestTransfer->getApiKeyId(), $authHeader);
    }
}
