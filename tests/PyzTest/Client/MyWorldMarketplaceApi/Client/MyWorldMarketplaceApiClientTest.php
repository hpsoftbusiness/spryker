<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Client\MyWorldMarketplaceApi\Client;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CustomerTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Client
 * @group MyWorldMarketplaceApi
 * @group Client
 * @group MyWorldMarketplaceApiClientTest
 * Add your own group annotations below this line
 */
class MyWorldMarketplaceApiClientTest extends Test
{
    /**
     * @var \PyzTest\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientTester
     */
    protected $tester;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $sut;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->sut = $this->tester->getLocator()->myWorldMarketplaceApi()->client();

        $this->tester->setDependency(
            MyWorldMarketplaceApiDependencyProvider::CLIENT_HTTP,
            $this->getHttpClientMock()
        );

        $this->setUpSession();
    }

    /**
     * @return void
     */
    public function testGetCustomerBalanceByCurrency()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setMyWorldCustomerId("1");

        $customerBalances = $this->sut->getCustomerBalanceByCurrency(
            $customerTransfer
        );

        $this->assertCount(4, $customerBalances);
        $this->assertSame(1, $customerBalances[0]->getPaymentOptionId());
        $this->assertSame('MyWorldEVoucher', $customerBalances[0]->getPaymentOptionName());
        $this->assertSame('EUR', $customerBalances[0]->getCurrencyID());
        $this->assertSame('2706.15', $customerBalances[0]->getAvailableBalance()->toString());
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function getHttpClientMock(): Client
    {
        $accessTokenResponse = '{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IlVWc3JQVnhueVRGMmxXNzFWVHMxUlJNdGZFWSJ9.eyJjbGllbnRfaWQiOiJzcHJ5a2VyX2FwaV9hdF9kZXYiLCJzY29wZSI6ImFwaXRlc3QiLCJpc3MiOiJpZC5seW9uZXNzLmludGVybmFsIiwiYXVkIjoibHlvIiwiZXhwIjoxNjIxMjY0Njg0LCJuYmYiOjE2MjEyNjI4ODR9.KOzoFRSZORAC04ygbV2JD2rm-dqDSqYtDEF1cPHgueBipFLqH3CEaRQohKCxA0tjoX1lPCE8tn5y-WejFa_IB9pnOaG9l6ikyFgz8w1ewNaSUpRSxfJ16mdTN4UWWUR6aOKEKXHME71p46nuUMHGSgNH9ocDVDWSW-n7IL04Sd2UZl7wKdqmuioXQx--6e0c7PAgfSN_fV_6R_82NIa1qMNftcXwgR8yK1Pb3O6y_cS_axpNfHMv11_XscF_2EjVtGdqA81poRADkXIml5UaWbEBquupanTklqwfPSUbXMLROu8A2T5SMv1WXURMBPMkwER_hK2E3B_jp5mYBiegVw","token_type":"Bearer","expires_in":1800,"refresh_token":null}';
        $customerBalanceResponse = '{"ResultCode":0,"ResultDescription":"Success","Data":[{"PaymentOptionID":1,"PaymentOptionName":"MyWorldEVoucher","CustomerID":"719d8907-1504-45f1-a41a-ac7c008d93d2","CurrencyID":"EUR","AvailableBalance":2706.15,"TargetCurrencyID":"EUR","TargetAvailableBalance":2706.15,"ExchangeRate":1.0},{"PaymentOptionID":6,"PaymentOptionName":"MyWorldCashbackAccount","CustomerID":"719d8907-1504-45f1-a41a-ac7c008d93d2","CurrencyID":"EUR","AvailableBalance":4531.98,"TargetCurrencyID":"EUR","TargetAvailableBalance":4531.98,"ExchangeRate":1.0},{"PaymentOptionID":10,"PaymentOptionName":"MyWorldBenefitVoucherAccount","CustomerID":"719d8907-1504-45f1-a41a-ac7c008d93d2","CurrencyID":"EUR","AvailableBalance":4648.31,"TargetCurrencyID":"EUR","TargetAvailableBalance":4648.31,"ExchangeRate":1.0},{"PaymentOptionID":9,"PaymentOptionName":"MyWorldShoppingPointsAccount","CustomerID":"719d8907-1504-45f1-a41a-ac7c008d93d2","CurrencyID":null,"AvailableBalance":310.25,"TargetCurrencyID":null,"TargetAvailableBalance":null,"ExchangeRate":null}]}';
        $mockHandler = new MockHandler([
            new Response(200, [], $accessTokenResponse),
            new Response(200, [], $customerBalanceResponse),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client([
            'handler' => $handlerStack,
        ]);
    }

    /**
     * @return void
     */
    private function setUpSession(): void
    {
        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }
}
