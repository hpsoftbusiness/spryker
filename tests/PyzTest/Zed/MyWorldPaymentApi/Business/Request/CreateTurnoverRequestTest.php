<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi\Business\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\CreateTurnoverRequest;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelper;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManager;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPaymentApi
 * @group Business
 * @group Request
 * @group CreateTurnoverRequestTest
 * Add your own group annotations below this line
 */
class CreateTurnoverRequestTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPaymentApi\MyWorldPaymentApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\CreateTurnoverRequest
     */
    protected $sut;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $myWorldMarketPlaceApiClientMock;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->myWorldMarketPlaceApiClientMock = $this->createMock(MyWorldMarketplaceApiClientInterface::class);
        $this->customerFacadeMock = $this->createMock(CustomerFacadeInterface::class);
    }

    /**
     * @dataProvider dataProviderOrder
     *
     * @param array $orderItemsToCreateTurnover
     * @param array $orderItemsData
     * @param string[] $expectedTurnoverAmounts
     *
     * @return void
     */
    public function testIfCreateTurnoverRequestCalculatesTurnoverAmountCorrect(
        array $orderItemsToCreateTurnover,
        array $orderItemsData,
        array $expectedTurnoverAmounts
    ): void {
        $this
            ->customerFacadeMock
            ->method('findByReference')
            ->willReturn((new CustomerTransfer())->setMyWorldCustomerId('1'));

        $this
            ->myWorldMarketPlaceApiClientMock
            ->method('performApiRequest')
            ->willReturnCallback(function (string $url, array $requestParams = []) use ($expectedTurnoverAmounts) {
                $requestBody = json_decode($requestParams['body'], true);
                $amount = $requestBody['Amount'];
                $segmentNumber = $requestBody['SegmentNumber'];

                $this->assertSame($expectedTurnoverAmounts[$segmentNumber], $amount);

                return (new MyWorldMarketplaceApiResponseTransfer())->setIsSuccess(true);
            });

        $orderItemIds = [];
        $idSalesOrder = $this->tester->createOrder();
        foreach ($orderItemsData as $index => $orderItemData) {
            $spySalesOrderItem = $this->tester->createSalesOrderItemForOrder($idSalesOrder, $orderItemData);
            if (in_array($index, $orderItemsToCreateTurnover)) {
                $orderItemIds[] = $spySalesOrderItem->getIdSalesOrderItem();
            }
        }

        $order = $this
            ->tester
            ->getLocator()
            ->sales()
            ->facade()
            ->findOrderByIdSalesOrder($idSalesOrder);

        foreach ($orderItemIds as $id) {
            $this->sut = CreateTurnoverRequest::create(
                new MyWorldMarketplaceApiConfig(),
                $this->myWorldMarketPlaceApiClientMock,
                $this->tester->getLocator()->utilEncoding()->service(),
                new MyWorldMarketplaceApiEntityManager(),
                new TurnoverRequestHelper($this->customerFacadeMock, new MyWorldMarketplaceApiConfig()),
                $order,
                $id
            );

            $this->sut->send();
        }

        $actualTurnoverCreatedItemIds = SpySalesOrderItemQuery::create()
            ->select(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM)
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByIsTurnoverCreated(true)
            ->find()
            ->toArray();

        ksort($orderItemIds);
        ksort($actualTurnoverCreatedItemIds);
        $this->assertEquals(
            $orderItemIds,
            $actualTurnoverCreatedItemIds
        );
    }

    /**
     * @return array[]
     */
    public function dataProviderOrder(): array
    {
        return [
            'two items with different segments' => [
                'orderItemsToCreateTurnover' => [0, 1],
                'orderItemsData' => [
                    [
                        'turnover_amount' => 1000,
                        'segment_number' => 1,
                    ],
                    [
                        'turnover_amount' => 2000,
                        'segment_number' => 4,
                    ],
                ],
                'expectedTurnoverAmounts' => [
                    // segment_number => turn_over_amount
                    1 => '10.00',
                    4 => '20.00',
                ],
            ],
        ];
    }
}
