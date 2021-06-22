<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence\Exception;

use Exception;

class ItemRefundEntitiesNotFoundException extends Exception
{
    protected const MESSAGE = 'Item refund entities not found for items with IDs %s .';

    /**
     * @param int[] $salesOrderItemIds
     */
    public function __construct(array $salesOrderItemIds)
    {
        parent::__construct($this->getFormattedMessage($salesOrderItemIds));
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return string
     */
    protected function getFormattedMessage(array $salesOrderItemIds): string
    {
        $implodedIds = implode(',', $salesOrderItemIds);

        return sprintf(self::MESSAGE, $implodedIds);
    }
}
