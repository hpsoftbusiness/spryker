<?php

namespace Pyz\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @var ProductAbstractManagerInterface
     */
    private $productAbstractManager;

    /**
     * @var ProductConcreteManagerInterface
     */
    private $productConcreteManager;

    /**
     * @var ProductQueryContainerInterface
     */
    private $productQueryContainer;

    /**
     * @var CheckerOrderItem
     */
    private $checkerOrderItem;

    /**
     * @var ProductConcreteActivatorInterface
     */
    private $productConcreteActivator;

    /**
     * ProductConcreteRemover constructor.
     *
     * @param ProductConcreteActivatorInterface $productConcreteActivator
     * @param ProductAbstractManagerInterface $productAbstractManager
     * @param ProductConcreteManagerInterface $productConcreteManager
     * @param ProductQueryContainerInterface $productQueryContainer
     * @param CheckerOrderItem $checkerOrderItem
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
     * @throws RemovedProductHasOrderException
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
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
    protected function getProductAbstractTransferById(int $idProductAbstract
    ): ?\Generated\Shared\Transfer\ProductAbstractTransfer {
        return $this->productAbstractManager->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
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
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    private function persistProductConcreteForSoftRemoved(ProductConcreteTransfer $productConcreteTransfer
    ): \Orm\Zed\Product\Persistence\SpyProduct {
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
     * @throws RemovedProductHasOrderException
     */
    private function validateIfCanRemove(string $sku): void
    {
        if ($this->checkerOrderItem->hasProductOrderItemBySku($sku)) {
            throw new RemovedProductHasOrderException();
        }
    }
}
