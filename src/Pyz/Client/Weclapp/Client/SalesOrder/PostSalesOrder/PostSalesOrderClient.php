<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\SalesOrder\PostSalesOrder;

use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Client\Weclapp\Client\SalesOrder\AbstractWeclappSalesOrderClient;

class PostSalesOrderClient extends AbstractWeclappSalesOrderClient implements PostSalesOrderClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/salesOrder';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function postSalesOrder(OrderTransfer $orderTransfer): void
    {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($orderTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getRequestBody(OrderTransfer $orderTransfer): array
    {
        return $this->salesOrderMapper
            ->mapOrderToWeclappSalesOrder($orderTransfer)
            ->toArray(true, true);
    }
}
