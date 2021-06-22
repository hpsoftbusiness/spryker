<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PartialRefundTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\PaymentRefundRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\MyWorldRefundProcessorPlugin;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface;
use Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface;
use PyzTest\Zed\MyWorldPayment\Communication\CommunicationDataHelper;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Communication
 * @group Plugin
 * @group Refund
 * @group MyWorldPaymentRefundProcessorPluginTest
 * Add your own group annotations below this line
 */
class MyWorldPaymentRefundProcessorPluginTest extends Test
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface
     */
    private $sut;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $myWorldPaymentApiFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createMyWorldPaymentRefundProcessorPlugin();
        $this->myWorldPaymentApiFacadeMock = $this->mockMyWorldPaymentApiFacade();
        $this->tester->setDependency(
            MyWorldPaymentDependencyProvider::FACADE_MY_WORLD_PAYMENT_API,
            $this->myWorldPaymentApiFacadeMock
        );
    }

    /**
     * @dataProvider provideRefundsData
     *
     * @param array $refundsData
     * @param array $expectedApiRequestData
     *
     * @return void
     */
    public function testProcessRefund(array $refundsData, array $expectedApiRequestData): void
    {
        $salesOrder = $this->tester->createSalesOrder();

        $refundTransfers = $this->buildRefundTransferCollection($refundsData, $salesOrder->getIdSalesOrder());

        $this->tester->createMyWorldPaymentEntity(
            $salesOrder->getIdSalesOrder(),
            CommunicationDataHelper::PAYMENT_DATA_DEFAULT_DATA
        );

        $this->myWorldPaymentApiFacadeMock
            ->expects(self::once())
            ->method('performCreateRefundApiCall')
            ->willReturnCallback(
                function (MyWorldApiRequestTransfer $apiRequestTransfer) use ($expectedApiRequestData): MyWorldApiResponseTransfer {
                    $refundRequestTransfer = $apiRequestTransfer->getPaymentRefundRequest();
                    self::assertNotNull($refundRequestTransfer);
                    self::assertEquals(
                        CommunicationDataHelper::PAYMENT_DATA_DEFAULT_DATA[PaymentDataResponseTransfer::PAYMENT_ID],
                        $refundRequestTransfer->getPaymentId()
                    );
                    self::assertEquals(
                        CommunicationDataHelper::PAYMENT_DATA_DEFAULT_DATA[PaymentDataResponseTransfer::CURRENCY_ID],
                        $refundRequestTransfer->getCurrency()
                    );

                    self::assertEquals(
                        $expectedApiRequestData[PaymentRefundRequestTransfer::AMOUNT],
                        $refundRequestTransfer->getAmount()
                    );
                    self::assertCount(
                        count($expectedApiRequestData[PaymentRefundRequestTransfer::PARTIAL_REFUNDS]),
                        $refundRequestTransfer->getPartialRefunds()
                    );

                    foreach ($refundRequestTransfer->getPartialRefunds() as $index => $partialRefundTransfer) {
                        $expectedPartialRefundData = $expectedApiRequestData[PaymentRefundRequestTransfer::PARTIAL_REFUNDS][$index] ?? null;
                        self::assertNotNull($expectedPartialRefundData);

                        self::assertEquals(
                            $expectedPartialRefundData[PartialRefundTransfer::PAYMENT_OPTION_ID],
                            $partialRefundTransfer->getPaymentOptionId()
                        );
                        self::assertEquals(
                            $expectedPartialRefundData[PartialRefundTransfer::AMOUNT],
                            $partialRefundTransfer->getAmount()
                        );
                        self::assertEquals(
                            $expectedPartialRefundData[PartialRefundTransfer::MAX_ALLOWED_AMOUNT],
                            $partialRefundTransfer->getMaxAllowedAmount()
                        );
                        self::assertEquals(
                            CommunicationDataHelper::PAYMENT_DATA_DEFAULT_DATA[PaymentDataResponseTransfer::CURRENCY_ID],
                            $partialRefundTransfer->getUnit()
                        );
                        self::assertEquals(
                            $this->tester->getModuleConfig()->getUnitTypeCurrency(),
                            $partialRefundTransfer->getUnitType()
                        );
                    }

                    return (new MyWorldApiResponseTransfer())->setIsSuccess(true);
                }
            );

        $refundResponseTransfer = $this->sut->processRefunds($refundTransfers);

        self::assertNotNull($refundResponseTransfer);
        self::assertTrue($refundResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testProcessRefundsReturnsNullIfNoProcessableRefundsProvided(): void
    {
        $refundTransfer = $this->tester->buildRefundTransfer([
            RefundTransfer::PAYMENT => [
                PaymentTransfer::PAYMENT_METHOD => AdyenConfig::PROVIDER_NAME,
                PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::ADYEN_CREDIT_CARD,
            ],
            RefundTransfer::AMOUNT => 2000,
        ]);

        $this->myWorldPaymentApiFacadeMock
            ->expects(self::never())
            ->method('performCreateRefundApiCall');

        $refundResponseTransfer = $this->sut->processRefunds([$refundTransfer]);

        self::assertNull($refundResponseTransfer);
    }

    /**
     * @return void
     */
    public function testProcessRefundsReturnsUnsuccessfulResponseIfApiRequestFailed(): void
    {
        $salesOrder = $this->tester->createSalesOrder();

        $refundTransfer = $this->tester->buildRefundTransfer([
            RefundTransfer::PAYMENT => [
                PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            ],
            RefundTransfer::AMOUNT => 2000,
            RefundTransfer::FK_SALES_ORDER => $salesOrder->getIdSalesOrder(),
        ]);

        $this->tester->createMyWorldPaymentEntity(
            $salesOrder->getIdSalesOrder(),
            CommunicationDataHelper::PAYMENT_DATA_DEFAULT_DATA
        );

        $this->myWorldPaymentApiFacadeMock
            ->expects(self::once())
            ->method('performCreateRefundApiCall')
            ->willReturnCallback(
                function (): MyWorldApiResponseTransfer {
                    return (new MyWorldApiResponseTransfer())->setIsSuccess(false);
                }
            );

        $refundResponseTransfer = $this->sut->processRefunds([$refundTransfer]);

        self::assertNotNull($refundResponseTransfer);
        self::assertFalse($refundResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    public function provideRefundsData(): array
    {
        return [
            'process benefit voucher payment refund' => [
                'refunds' => [
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 800,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                ],
                'expected api request data' => [
                    PaymentRefundRequestTransfer::AMOUNT => 800,
                    PaymentRefundRequestTransfer::PARTIAL_REFUNDS => [
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 800,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => 800,
                        ],
                    ],
                ],
            ],
//            -----------------
            'process benefit voucher with cashback' => [
                'refunds' => [
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 800,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 1000,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                ],
                'expected api request data' => [
                    PaymentRefundRequestTransfer::AMOUNT => 1800,
                    PaymentRefundRequestTransfer::PARTIAL_REFUNDS => [
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 800,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => 800,
                        ],
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_CASHBACK,
                            PartialRefundTransfer::AMOUNT => 1000,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => null,
                        ],
                    ],
                ],
            ],
//            -----------------
            'process benefit voucher with evoucher' => [
                'refunds' => [
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 800,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 1000,
                    ],
                ],
                'expected api request data' => [
                    PaymentRefundRequestTransfer::AMOUNT => 1800,
                    PaymentRefundRequestTransfer::PARTIAL_REFUNDS => [
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 800,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => 800,
                        ],
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_E_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 1000,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => null,
                        ],
                    ],
                ],
            ],
//            -----------------
            'process benefit voucher with evoucher on marketer behalf' => [
                'refunds' => [
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 800,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 1000,
                    ],
                ],
                'expected api request data' => [
                    PaymentRefundRequestTransfer::AMOUNT => 1800,
                    PaymentRefundRequestTransfer::PARTIAL_REFUNDS => [
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 800,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => 800,
                        ],
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_E_VOUCHER_MARKETER,
                            PartialRefundTransfer::AMOUNT => 1000,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => null,
                        ],
                    ],
                ],
            ],
//            -----------------
            'process benefit voucher with cashback and adyen' => [
                'refunds' => [
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 800,
                        RefundTransfer::ITEMS => [
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 500,
                                ],
                            ],
                            [
                                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                    BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                ],
                            ],
                        ],
                    ],
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => AdyenConfig::PROVIDER_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::ADYEN_CREDIT_CARD,
                        ],
                        RefundTransfer::AMOUNT => 2000,
                    ],
                    [
                        RefundTransfer::PAYMENT => [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
                            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                        ],
                        RefundTransfer::AMOUNT => 1000,
                    ],
                ],
                'expected api request data' => [
                    PaymentRefundRequestTransfer::AMOUNT => 1800,
                    PaymentRefundRequestTransfer::PARTIAL_REFUNDS => [
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                            PartialRefundTransfer::AMOUNT => 800,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => 800,
                        ],
                        [
                            PartialRefundTransfer::PAYMENT_OPTION_ID => CommunicationDataHelper::PAYMENT_OPTION_ID_CASHBACK,
                            PartialRefundTransfer::AMOUNT => 1000,
                            PartialRefundTransfer::MAX_ALLOWED_AMOUNT => null,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $refundsData
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    private function buildRefundTransferCollection(array $refundsData, int $idSalesOrder): array
    {
        return array_map(
            function (array $refundOverride) use ($idSalesOrder): RefundTransfer {
                return $this->tester->buildRefundTransfer($refundOverride)->setFkSalesOrder($idSalesOrder);
            },
            $refundsData
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface
     */
    private function createMyWorldPaymentRefundProcessorPlugin(): RefundProcessorPluginInterface
    {
        return new MyWorldRefundProcessorPlugin();
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockMyWorldPaymentApiFacade(): MyWorldPaymentApiFacadeInterface
    {
        return $this->createMock(MyWorldPaymentApiFacadeInterface::class);
    }
}
