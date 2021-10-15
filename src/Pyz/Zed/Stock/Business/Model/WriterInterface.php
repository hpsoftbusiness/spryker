<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Stock\Business\Model\WriterInterface as SprykerWriterInterface;

interface WriterInterface extends SprykerWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateOrCreateStockProduct(StockProductTransfer $transferStockProduct): int;
}
