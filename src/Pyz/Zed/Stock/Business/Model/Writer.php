<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException;
use Spryker\Zed\Stock\Business\Model\Writer as SprykerWriter;

class Writer extends SprykerWriter implements WriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateOrCreateStockProduct(StockProductTransfer $transferStockProduct): int
    {
        try {
            $stockProductId = $this->stockProductReader->getIdStockProduct(
                $transferStockProduct->getSku(),
                $transferStockProduct->getStockType()
            );
            $transferStockProduct->setIdStockProduct($stockProductId);

            return $this->updateStockProduct($transferStockProduct);
        } catch (StockProductNotFoundException $e) {
            return $this->createStockProduct($transferStockProduct);
        }
    }
}
