<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CustomerPage\Security\Guard;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Client\Sso\SsoClient;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider;
use Pyz\Yves\CustomerPage\Security\Guard\SsoAuthenticator;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\ZedRequest\Plugin\AuthTokenHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\Plugin\RequestIdHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\ZedRequestDependencyProvider;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CustomerPage
 * @group Security
 * @group Guard
 * @group SsoAuthenticatorTest
 * Add your own group annotations below this line
 */
class SsoAuthenticatorTest extends Unit
{
    /**
     * @var \PyzTest\Yves\CustomerPage\CustomerPageProcessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CustomerPage\Security\Guard\SsoAuthenticator
     */
    protected $sut;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Pyz\Client\Sso\SsoClientInterface
     */
    protected $ssoClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Pyz\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider
     */
    protected $customerUserProviderMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->setUpMocks();
        $this->setUpSession();
        $this->setUpDependencies();
        $this->setUpSut();
    }

    /**
     * @group testEliteClubGroupIsCreated
     *
     * @return void
     */
    public function testEliteClubGroupIsCreated()
    {
        // Arrange
        $this
            ->tester
            ->haveCustomerGroupByName('Marketers');
        $this
            ->tester
            ->haveCustomerGroupByName('EliteClub_Deal');

        $this->ssoClientMock
            ->method('getCustomerInformationBySsoAccessToken')
            ->willReturn(
                $this->getResponseForEliteClub()
            );

        // Act
        $this->sut->getUser(
            $this->tester->getSsoAccessTokenTransfer(),
            $this->customerUserProviderMock
        );

        // Assert
        $customer = $this
            ->tester
            ->loadCustomerByEmail('xaowwgucgtmu@a-gmail.com');
        $this->assertNotNull($customer);

        $countCustomerGroups = $customer->countSpyCustomerGroupToCustomers();
        $this->assertSame(2, $countCustomerGroups);

        $customerGroups = $customer->getSpyCustomerGroupToCustomers();
        foreach ($customerGroups as $customerGroupToCustomer) {
            $this->assertTrue(
                in_array(
                    $customerGroupToCustomer
                        ->getCustomerGroup()
                        ->getName(),
                    [
                        'Marketers',
                        'EliteClub_Deal',
                    ]
                )
            );
        }
    }

    /**
     * @return void
     */
    private function setUpMocks(): void
    {
        $this->ssoClientMock = $this->createMock(SsoClient::class);
        $this->customerUserProviderMock = new CustomerUserProvider(
            $this->ssoClientMock
        );
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

    /**
     * @return void
     */
    private function setUpDependencies(): void
    {
        $this->tester->setDependency(
            MyWorldPaymentDependencyProvider::FACADE_MY_WORLD_PAYMENT_API,
            $this->ssoClientMock
        );
        $this->tester->setDependency(ZedRequestDependencyProvider::PLUGINS_HEADER_EXPANDER, [
            new AuthTokenHeaderExpanderPlugin(),
            new RequestIdHeaderExpanderPlugin(),
        ]);
        $this->tester->getContainer()->set(
            'security.http_utils',
            Stub::makeEmpty(HttpUtils::class)
        );
        $this->tester->getContainer()->set(
            'flash_messenger',
            Stub::makeEmpty(FlashMessengerInterface::class)
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    private function getResponseForEliteClub(): CustomerTransfer
    {
        $jsonResponse = '{
            "ResultCode": 0,
            "ResultDescription": "Success",
            "Data": {
                "CustomerID": "9d07ad37-fe3d-4fb4-b28b-a02e00fb752b",
                "CustomerNumber": "351.000.002.109",
                "CardNumber": "7710044075461",
                "Firstname": "hjwj Nxwxp U q gcjAptSi",
                "Lastname": "Txrttzr",
                "BirthdayDate": "1983-04-25",
                "CountryID": "PT",
                "Email": "xaowwgucgtmu@a-gmail.com",
                "MobilePhoneNumber": "00351918989890",
                "Status": "Active",
                "AvailableCashbackAmount": 26.6009,
                "AvailableCashbackCurrency": "EUR",
                "AvailableShoppingPointAmount": 163.306533,
                "CustomerType": 3,
                "HasPlusPackage": false,
                "PlusPackages": [],
                "AvailableBenefitVoucherAmount": 1040,
                "AvailableBenefitVoucherCurrency": "EUR",
                "EliteClub": {
                    "Type": 191206,
                    "Name": "EliteClubExecutiveDownPayment",
                    "ValidTo": "2021-11-30T14:24:34"
                },
                "IsPartnerConnected": false
            }
        }';

        return $this
            ->tester
            ->getCustomerInformationMapper()
            ->mapDataToCustomerTransfer(
                json_decode($jsonResponse, true)
            );
    }

    /**
     * @return void
     */
    private function setUpSut(): void
    {
        $this->sut = new SsoAuthenticator(
            $this->tester->getContainer()->get('security.http_utils'),
            $this->tester->getLocator()->sso()->client(),
            new CustomerAuthenticationSuccessHandler(
                $this->tester->getContainer()->get('flash_messenger')
            ),
            new CustomerAuthenticationFailureHandler(
                $this->tester->getContainer()->get('flash_messenger')
            ),
            'de_DE',
            $this->tester->getLocator()->customer()->client()
        );
    }
}
