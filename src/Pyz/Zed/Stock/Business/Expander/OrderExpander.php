<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Pyz\Zed\Stock\Persistence\StockRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Pyz\Zed\Stock\Persistence\StockRepositoryInterface
     */
    private $stockRepository;

    /**
     * @param \Pyz\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     */
    public function __construct(StockRepositoryInterface $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithStock(OrderTransfer $orderTransfer): OrderTransfer
    {
        $idsProductConcrete = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $idsProductConcrete[] = $itemTransfer->getId();
        }

        $stockCriteriaFilterTransfer = (new StockCriteriaFilterTransfer())
            ->setIdsProductConcrete([$idsProductConcrete]);

        $stockTransfers = $this->stockRepository->getStocksByCriteriaFilter($stockCriteriaFilterTransfer);

        return $orderTransfer->setStocks(new ArrayObject($stockTransfers));
    }
}
