<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Business\Oms\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentAdyenBuilder;
use Generated\Shared\Transfer\PaymentAdyenTransfer;
use SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Business
 * @group Oms
 * @group Mapper
 * @group AbstractCommandMapperTest
 * Add your own group annotations below this line
 */
abstract class AbstractCommandMapperTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Adyen\AdyenBusinessTester
     */
    protected $tester;

    /**
     * @return \SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function mockAdyenReader(): AdyenReaderInterface
    {
        $readerMock = $this->createMock(AdyenReaderInterface::class);
        $readerMock
            ->method('getPaymentAdyenByOrderTransfer')
            ->willReturnCallback(function () {
                return $this->buildPaymentAdyenTransfer([
                    PaymentAdyenTransfer::SPLIT_MARKETPLACE_REFERENCE => $this->tester::MARKETPLACE_REFERENCE,
                    PaymentAdyenTransfer::SPLIT_COMMISSION_REFERENCE => $this->tester::COMMISSION_REFERENCE,
                    PaymentAdyenTransfer::PSP_REFERENCE => 'psp_reference',
                    PaymentAdyenTransfer::REFERENCE => 'reference',
                ]);
            });

        return $readerMock;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentAdyenTransfer
     */
    private function buildPaymentAdyenTransfer(array $override): PaymentAdyenTransfer
    {
        return (new PaymentAdyenBuilder($override))->build();
    }
}
