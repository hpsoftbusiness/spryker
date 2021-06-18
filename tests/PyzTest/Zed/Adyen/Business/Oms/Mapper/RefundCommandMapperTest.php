<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Business\Oms\Mapper;

use Generated\Shared\DataBuilder\RefundBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use Pyz\Zed\Adyen\AdyenConfig;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapper;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Business
 * @group Oms
 * @group Mapper
 * @group RefundCommandMapperTest
 * Add your own group annotations below this line
 */
class RefundCommandMapperTest extends AbstractCommandMapperTest
{
    /**
     * @var \Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapper
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createRefundCommandMapper();
    }

    /**
     * @return void
     */
    public function testBuildRequestTransferWithSplits(): void
    {
        $orderTransfer = $this->tester->buildOrderTransfer([
            OrderTransfer::CURRENCY => [
                CurrencyTransfer::CODE => $this->tester::CURRENCY_CODE_EUR,
            ],
        ]);
        $refundTransfer = $this->buildRefundTransfer([
            RefundTransfer::AMOUNT => 1500,
        ]);

        $adyenApiRequestTransfer = $this->sut->buildRequestTransfer($orderTransfer, $refundTransfer);

        $refundRequest = $adyenApiRequestTransfer->getRefundRequest();
        self::assertNotNull($refundRequest);

        self::assertCount(2, $refundRequest->getSplits());

        $marketplaceSplitTransfer = $this->tester->findSplitTransferByType(
            $refundRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_MARKETPLACE
        );
        self::assertNotNull($marketplaceSplitTransfer);
        self::assertEquals($this->tester::MARKETPLACE_REFERENCE, $marketplaceSplitTransfer->getReference());

        $commissionSplitTransfer = $this->tester->findSplitTransferByType(
            $refundRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_COMMISSION
        );
        self::assertNotNull($marketplaceSplitTransfer);
        self::assertEquals($this->tester::COMMISSION_REFERENCE, $commissionSplitTransfer->getReference());
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    private function buildRefundTransfer(array $override): RefundTransfer
    {
        return (new RefundBuilder($override))->build();
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapper
     */
    private function createRefundCommandMapper(): RefundCommandMapper
    {
        return new RefundCommandMapper(
            $this->mockAdyenReader(),
            new AdyenConfig()
        );
    }
}
