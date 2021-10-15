<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Plugin\Command;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Zed\Sales\Communication\Exception\CommentTransferNotFoundException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Pyz\Zed\Sales\SalesConfig getConfig()
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class SaveDeliveryTrackingCodeCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    public const COMMENT_TRANSFER_KEY = 'comment_transfer';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $commentTransfer = $data[static::COMMENT_TRANSFER_KEY] ?? null;

        if (!$commentTransfer || !$commentTransfer instanceof CommentTransfer) {
            throw new CommentTransferNotFoundException();
        }

        $commentTransfer->setFkSalesOrder($orderEntity->getIdSalesOrder());
        $this->getFacade()->saveComment($commentTransfer);

        return [];
    }
}
