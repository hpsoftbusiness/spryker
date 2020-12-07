<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface as SprykerStockRepositoryInterface;

interface StockRepositoryInterface extends SprykerStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array;
}
