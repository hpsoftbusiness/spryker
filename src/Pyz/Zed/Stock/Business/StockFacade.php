<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Stock\Business\StockFacade as SprykerStockFacade;

/**
 * @method \Pyz\Zed\Stock\Business\StockBusinessFactory getFactory()
 * @method \Pyz\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 */
class StockFacade extends SprykerStockFacade implements StockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithStock(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandOrderWithStock($orderTransfer);
    }

    /**
     * @param string $idWeclappWarehouse
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByIdWeclappWarehouse(string $idWeclappWarehouse): ?StockTransfer
    {
        return $this->getRepository()->findStockByIdWeclappWarehouse($idWeclappWarehouse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateOrCreateStockProduct(StockProductTransfer $transferStockProduct): int
    {
        return $this->getFactory()->createWriterModel()->updateOrCreateStockProduct($transferStockProduct);
    }
}
