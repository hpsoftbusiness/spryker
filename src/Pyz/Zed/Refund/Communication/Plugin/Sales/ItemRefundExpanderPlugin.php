<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Sales;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface getRepository()()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class ItemRefundExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->expandItemWithRefunds($itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function expandItemWithRefunds(ItemTransfer $itemTransfer): void
    {
        $itemRefundsTransfers = $this->getRepository()->findOrderItemRefundsByIdSalesOrderItem(
            $itemTransfer->getIdSalesOrderItem()
        );

        $itemTransfer->setRefunds(new ArrayObject($itemRefundsTransfers));
    }
}
