<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Business\Expander;

use ArrayObject;
use Pyz\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer[][]|\ArrayObject[]
     */
    protected static $productCategoryToIdProductAbstractBuffer = [];

    /**
     * @var \Pyz\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected $productCategoryRepository;

    /**
     * @param \Pyz\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $productCategoryRepository
     */
    public function __construct(
        ProductCategoryRepositoryInterface $productCategoryRepository
    ) {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithCategories(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $categoryTransfer = $this->getCategoryTransfersByIdProductAbstract(
                $itemTransfer->getProductConcrete()->getFkProductAbstract()
            );

            if (!$categoryTransfer) {
                continue;
            }

            $itemTransfer->setCategories($categoryTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \ArrayObject
     */
    protected function getCategoryTransfersByIdProductAbstract(int $idProductAbstract): ArrayObject
    {
        if (isset(static::$productCategoryToIdProductAbstractBuffer[$idProductAbstract])) {
            return static::$productCategoryToIdProductAbstractBuffer[$idProductAbstract];
        }

        $categoryCollectionTransfer = $this->productCategoryRepository
            ->getCategoryTransferCollectionByIdProductAbstractWithoutLocale($idProductAbstract);
        static::$productCategoryToIdProductAbstractBuffer[$idProductAbstract] = $categoryCollectionTransfer
            ->getCategories();

        return static::$productCategoryToIdProductAbstractBuffer[$idProductAbstract];
    }
}
