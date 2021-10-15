<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Importer;

use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WeclappWarehouseStockTransfer;
use Generated\Shared\Transfer\WeclappWarehouseTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\Stock\Business\StockFacadeInterface;

class StockImporter implements StockImporterInterface
{
    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Pyz\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Pyz\Zed\Stock\Business\StockFacadeInterface $stockFacade
     * @param \Pyz\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        StockFacadeInterface $stockFacade,
        ProductFacadeInterface $productFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->stockFacade = $stockFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function changeStocksByWeclapp(array $restWeclappWebhooksAttributesTransfer): void
    {
        foreach ($restWeclappWebhooksAttributesTransfer as $restWeclappWebhookAttributesTransfer) {
            $weclappWarehouseStockTransfer = $this->getWeclappWarehouseStock($restWeclappWebhookAttributesTransfer);
            if ($weclappWarehouseStockTransfer) {
                $this->updateProductStock($weclappWarehouseStockTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseStockTransfer|null
     */
    protected function getWeclappWarehouseStock(
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
    ): ?WeclappWarehouseStockTransfer {
        $weclappWarehouseStockTransfer = new WeclappWarehouseStockTransfer();
        $weclappWarehouseStockTransfer->setId($restWeclappWebhooksAttributesTransfer->getEntityIdOrFail());

        return $this->weclappClient->getWarehouseStock($weclappWarehouseStockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer|null $weclappWarehouseStockTransfer
     *
     * @return void
     */
    protected function updateProductStock(?WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer): void
    {
        $stockTransfer = $this->getStock($weclappWarehouseStockTransfer);
        if (!$stockTransfer) {
            return;
        }

        if (!$this->productFacade->findProductConcreteIdBySku(
            $weclappWarehouseStockTransfer->getArticleNumberOrFail()
        )) {
            return;
        }

        $stockProductTransfer = new StockProductTransfer();
        $stockProductTransfer->setQuantity($weclappWarehouseStockTransfer->getQuantityOrFail())
            ->setStockType($stockTransfer->getNameOrFail())
            ->setSku($weclappWarehouseStockTransfer->getArticleNumberOrFail());

        $this->stockFacade->updateOrCreateStockProduct($stockProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    protected function getStock(WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer): ?StockTransfer
    {
        // get stock by id weclapp warehouse
        $stockTransfer = $this->stockFacade->findStockByIdWeclappWarehouse($weclappWarehouseStockTransfer->getWarehouseId());
        if ($stockTransfer) {
            return $stockTransfer;
        }

        // get stock by name
        $weclappWarehouseTransfer = new WeclappWarehouseTransfer();
        $weclappWarehouseTransfer->setId($weclappWarehouseStockTransfer->getWarehouseId());
        $weclappWarehouseTransfer = $this->weclappClient->getWarehouse($weclappWarehouseTransfer);
        $stockTransfer = $this->stockFacade->findStockByName(trim($weclappWarehouseTransfer->getName()));
        if (!$stockTransfer) {
            return null;
        }

        // save id weclapp warehouse
        $stockTransfer->setIdWeclappWarehouse($weclappWarehouseTransfer->getId());
        $this->stockFacade->updateStock($stockTransfer);

        return $stockTransfer;
    }
}
