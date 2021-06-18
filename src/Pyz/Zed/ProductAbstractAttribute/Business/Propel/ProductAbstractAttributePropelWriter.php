<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Business\Propel;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface;
use Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface;

class ProductAbstractAttributePropelWriter implements ProductAbstractAttributePropelWriterInterface
{
    /**
     * @var \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Pyz\Zed\Product\Business\ProductFacadeInterface $productFacade
     * @param \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface $entityManager
     * @param \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface $repository
     */
    public function __construct(
        ProductFacadeInterface $productFacade,
        ProductAbstractAttributeEntityManagerInterface $entityManager,
        ProductAbstractAttributeRepositoryInterface $repository
    ) {
        $this->productFacade = $productFacade;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function save(array $productAbstractIds): void
    {
        $productAbstractTransfers = $this
            ->productFacade
            ->getRawProductAbstractsByProductAbstractIds($productAbstractIds);
        $this->saveData($productAbstractTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $productAbstractTransfers
     *
     * @return void
     */
    protected function saveData(array $productAbstractTransfers): void
    {
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $productAbstractId = $productAbstractTransfer->getIdProductAbstract();
            $entityTransfer = $this
                ->repository
                ->findProductAbstractAttributeByFkProductAbstract($productAbstractId);

            $benefitStoreValue = $this->getBenefitStoreAttributeValue($productAbstractTransfer);
            $shoppingPointValue = $this->getShoppingPointAttributeValue($productAbstractTransfer);
            $entityTransfer->setBenefitStore($benefitStoreValue);
            $entityTransfer->setShoppingPoint($shoppingPointValue);
            $entityTransfer->setFkProductAbstract($productAbstractId);

            $this->entityManager->saveProductAbstractAttribute($entityTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    protected function getBenefitStoreAttributeValue(ProductAbstractTransfer $productAbstractTransfer): bool
    {
        $attributes = $this->getProductAttributes($productAbstractTransfer);

        return $attributes['benefit_store'] ?? false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    protected function getShoppingPointAttributeValue(ProductAbstractTransfer $productAbstractTransfer): bool
    {
        $attributes = $this->getProductAttributes($productAbstractTransfer);

        return $attributes['shopping_point_store'] ?? false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    protected function getProductAttributes(ProductAbstractTransfer $productAbstractTransfer): array
    {
        /** @var array|string $attributes */
        $attributes = $productAbstractTransfer->getAttributes();
        if (is_string($attributes)) {
            $attributes = json_decode($attributes, true);
        }

        return $attributes;
    }
}
