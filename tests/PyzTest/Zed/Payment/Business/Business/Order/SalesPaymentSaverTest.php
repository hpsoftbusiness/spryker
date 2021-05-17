<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Payment\Business\Business\Order;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Propel\Runtime\Collection\Collection;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\Payment\Business\Order\SalesPaymentSaver;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Business
 * @group Order
 * @group SalesPaymentSaverTest
 * Add your own group annotations below this line
 */
class SalesPaymentSaverTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Payment\PaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->sut = $this->createSalesPaymentSaver();
    }

    /**
     * @return void
     */
    public function testPaymentAvailableAmountAndIsLimitedAmountFlagPersistedToDatabase(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $checkoutResponseTransfer = $this->buildCheckoutResponseTransfer($salesOrderEntity->getIdSalesOrder());
        $quoteTransfer = $this->buildQuoteTransfer();
        $quoteTransfer->setPayment($this->createPaymentTransfer(true, 500));

        $this->sut->saveOrderPayments($quoteTransfer, $checkoutResponseTransfer);

        $payments = $this->findSalesPaymentsByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        self::assertCount(1, $payments);
        self::assertEquals(500, $payments[0]->getAvailableAmount());
        self::assertTrue($payments[0]->getIsLimitedAmount());
    }

    /**
     * @return void
     */
    public function testPaymentAvailableAmountNotPersistedIfIsLimitedAmountFlagSetFalse(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $checkoutResponseTransfer = $this->buildCheckoutResponseTransfer($salesOrderEntity->getIdSalesOrder());
        $quoteTransfer = $this->buildQuoteTransfer();
        $quoteTransfer->setPayment($this->createPaymentTransfer(false, 500));

        $this->sut->saveOrderPayments($quoteTransfer, $checkoutResponseTransfer);

        $payments = $this->findSalesPaymentsByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        self::assertCount(1, $payments);
        self::assertNull($payments[0]->getAvailableAmount());
        self::assertFalse($payments[0]->getIsLimitedAmount());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    private function findSalesPaymentsByIdSalesOrder(int $idSalesOrder): Collection
    {
        return SpySalesPaymentQuery::create()
            ->filterByFkSalesOrder($idSalesOrder)
            ->find();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function buildQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())->build();
    }

    /**
     * @param bool $isLimitedAmount
     * @param int $availableAmount
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function createPaymentTransfer(bool $isLimitedAmount, int $availableAmount): PaymentTransfer
    {
        return $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_METHOD_NAME => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::PAYMENT_PROVIDER => DummyPrepaymentConfig::PROVIDER_NAME,
            PaymentTransfer::PAYMENT_METHOD => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::PAYMENT_SELECTION => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::AMOUNT => 500,
            PaymentTransfer::IS_LIMITED_AMOUNT => $isLimitedAmount,
            PaymentTransfer::AVAILABLE_AMOUNT => $availableAmount,
        ]);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private function buildCheckoutResponseTransfer(int $idSalesOrder): CheckoutResponseTransfer
    {
        return (new CheckoutResponseBuilder([
            CheckoutResponseTransfer::SAVE_ORDER => [
                SaveOrderTransfer::ID_SALES_ORDER => $idSalesOrder,
            ],
        ]))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(array $override): PaymentTransfer
    {
        return (new PaymentBuilder($override))->build();
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    private function createSalesPaymentSaver(): SalesPaymentSaverInterface
    {
        return new SalesPaymentSaver(
            $this->tester->getLocator()->payment()->queryContainer()
        );
    }
}
