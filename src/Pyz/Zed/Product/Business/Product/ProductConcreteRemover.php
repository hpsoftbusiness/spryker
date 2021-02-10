<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Pyz\Zed\Product\Business\Exception\RemovedProductHasOrderException;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteRemover
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    private $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    private $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    private $productQueryContainer;

    /**
     * @var \Pyz\Zed\Product\Business\Product\CheckerOrderItem
     */
    private $checkerOrderItem;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface
     */
    private $productConcreteActivator;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface $productConcreteActivator
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Pyz\Zed\Product\Business\Product\CheckerOrderItem $checkerOrderItem
     */
    public function __construct(
        ProductConcreteActivatorInterface $productConcreteActivator,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductQueryContainerInterface $productQueryContainer,
        CheckerOrderItem $checkerOrderItem
    ) {
        $this->productConcreteActivator = $productConcreteActivator;
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productQueryContainer = $productQueryContainer;
        $this->checkerOrderItem = $checkerOrderItem;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function markAbstractProductAsRemoved(int $idProductAbstract)
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($idProductAbstract) {
                $this->executeMarkAbstractProductAsRemovedTransaction($idProductAbstract);
            }
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function executeMarkAbstractProductAsRemovedTransaction(int $idProductAbstract): void
    {
        $productAbstractTransfer = $this->getProductAbstractTransferById($idProductAbstract);

        $productAbstractTransfer->setIsRemoved(true);
        $productAbstractTransfer->setSku($this->generateRemovedSkuValue($productAbstractTransfer->getSku()));
        $this->productAbstractManager->saveProductAbstract($productAbstractTransfer);

        $productConcreteTransfers = $this->getProductConcreteTransfers($idProductAbstract);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->validateIfCanRemove($productConcreteTransfer->getSku());

            $productConcreteTransfer->setIsRemoved(true);
            $productConcreteTransfer->setSku($this->generateRemovedSkuValue($productConcreteTransfer->getSku()));
            $this->persistProductConcreteForSoftRemoved($productConcreteTransfer);

            $this->productConcreteActivator->deactivateProductConcrete(
                $productConcreteTransfer->getIdProductConcrete()
            );
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    protected function getProductAbstractTransferById(int $idProductAbstract): ?ProductAbstractTransfer
    {
        return $this->productAbstractManager->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductConcreteTransfers(int $idProductAbstract): array
    {
        return $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function generateRemovedSkuValue(string $sku): string
    {
        return sprintf('%s-removed-%s', $sku, (string)time());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    private function persistProductConcreteForSoftRemoved(ProductConcreteTransfer $productConcreteTransfer): SpyProduct
    {
        $productConcreteEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productConcreteEntity->setSku($productConcreteTransfer->getSku());
        $productConcreteEntity->setIsRemoved($productConcreteTransfer->getIsRemoved());

        $productConcreteEntity->save();

        return $productConcreteEntity;
    }

    /**
     * @param string $sku
     *
     * @throws \Pyz\Zed\Product\Business\Exception\RemovedProductHasOrderException
     *
     * @return void
     */
    private function validateIfCanRemove(string $sku): void
    {
        if ($this->checkerOrderItem->hasProductOrderItemBySku($sku)) {
            throw new RemovedProductHasOrderException();
        }
    }
}
