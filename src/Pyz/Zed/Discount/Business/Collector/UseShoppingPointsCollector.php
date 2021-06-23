<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Discount\Business\Collector\BaseCollector;
use Spryker\Zed\Discount\Business\Collector\CollectorInterface;

class UseShoppingPointsCollector extends BaseCollector implements CollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUseShoppingPoints() || !$this->assertItemShoppingPointsDeal($itemTransfer)) {
                continue;
            }

            $discountableItems[] = $this->createDiscountableItemForItemTransfer($itemTransfer);
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertItemShoppingPointsDeal(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireShoppingPointsDeal();
            $itemTransfer->getShoppingPointsDeal()->requireIsActive();
            $itemTransfer->getShoppingPointsDeal()->requireShoppingPointsQuantity();
            $itemTransfer->getShoppingPointsDeal()->requirePrice();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }

        return true;
    }
}
