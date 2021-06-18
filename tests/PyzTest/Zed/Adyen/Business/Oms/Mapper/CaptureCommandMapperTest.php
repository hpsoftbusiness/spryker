<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Business\Oms\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\Adyen\AdyenConfig;
use Pyz\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use SprykerEco\Zed\Adyen\Business\Exception\AdyenMethodMapperException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Business
 * @group Oms
 * @group Mapper
 * @group CaptureCommandMapperTest
 * Add your own group annotations below this line
 */
class CaptureCommandMapperTest extends AbstractCommandMapperTest
{
    /**
     * @var \Pyz\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createCaptureCommandMapper();
    }

    /**
     * @return void
     */
    public function testBuildRequestTransferWithSplitsAndAmountFromPayment(): void
    {
        $orderTransfer = $this->tester->buildOrderTransfer([
            OrderTransfer::CURRENCY_ISO_CODE => $this->tester::CURRENCY_CODE_EUR,
            OrderTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => SharedAdyenConfig::ADYEN_CREDIT_CARD,
                    PaymentTransfer::PAYMENT_PROVIDER => SharedAdyenConfig::PROVIDER_NAME,
                    PaymentTransfer::AMOUNT => 2000,
                ],
            ],
            OrderTransfer::ITEMS => [
                [
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
        ]);

        $adyenApiRequestTransfer = $this->sut->buildRequestTransfer([new SpySalesOrderItem()], $orderTransfer);

        $captureRequest = $adyenApiRequestTransfer->getCaptureRequest();
        self::assertNotNull($captureRequest);

        self::assertEquals(2000, $captureRequest->getModificationAmount()->getValue());
        self::assertEquals($this->tester::CURRENCY_CODE_EUR, $captureRequest->getModificationAmount()->getCurrency());
        self::assertCount(2, $captureRequest->getSplits());

        $marketplaceSplitTransfer = $this->tester->findSplitTransferByType(
            $captureRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_MARKETPLACE
        );
        self::assertNotNull($marketplaceSplitTransfer);
        self::assertEquals($this->tester::MARKETPLACE_REFERENCE, $marketplaceSplitTransfer->getReference());

        $commissionSplitTransfer = $this->tester->findSplitTransferByType(
            $captureRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_COMMISSION
        );
        self::assertNotNull($marketplaceSplitTransfer);
        self::assertEquals($this->tester::COMMISSION_REFERENCE, $commissionSplitTransfer->getReference());
    }

    /**
     * @return void
     */
    public function testBuildRequestTransferThrowsExceptionIfAdyenPaymentMissing(): void
    {
        self::expectException(AdyenMethodMapperException::class);

        $orderTransfer = $this->tester->buildOrderTransfer([
            OrderTransfer::CURRENCY_ISO_CODE => $this->tester::CURRENCY_CODE_EUR,
            OrderTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
                    PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                    PaymentTransfer::AMOUNT => 2000,
                ],
            ],
            OrderTransfer::ITEMS => [
                [
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
        ]);

        $this->sut->buildRequestTransfer([new SpySalesOrderItem()], $orderTransfer);
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper
     */
    private function createCaptureCommandMapper(): CaptureCommandMapper
    {
        return new CaptureCommandMapper(
            $this->mockAdyenReader(),
            new AdyenConfig()
        );
    }
}
