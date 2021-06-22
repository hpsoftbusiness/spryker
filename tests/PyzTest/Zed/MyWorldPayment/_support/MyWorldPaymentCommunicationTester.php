<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MyWorldPaymentCommunicationTester extends Actor
{
    use _generated\MyWorldPaymentCommunicationTesterActions;

    /**
     * @param array $paymentsData
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSalesOrder(array $paymentsData = []): SpySalesOrder
    {
        $salesOrder = $this->haveSalesOrderEntity();

        foreach ($paymentsData as $paymentDataOverride) {
            $this->haveSalesPaymentEntity($salesOrder->getIdSalesOrder(), $paymentDataOverride);
        }

        return $salesOrder;
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function buildCalculableObjectTransfer(array $overrideData): CalculableObjectTransfer
    {
        return (new CalculableObjectBuilder($overrideData))->build();
    }
}
