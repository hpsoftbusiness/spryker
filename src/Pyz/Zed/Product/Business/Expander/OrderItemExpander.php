<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Expander;

use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductConcrete(array $itemTransfers): array
    {
        $productConcreteSkus = $this->extractProductConcreteSkus($itemTransfers);
        $productConcreteTransfers = $this->productRepository->getProductConcretesByConcreteSkus($productConcreteSkus);

        $mappedProductConcreteTransfers = $this->mapProductConcreteTransfersBySku($productConcreteTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteTransfer = $mappedProductConcreteTransfers[$itemTransfer->getSku()] ?? null;

            if (!$productConcreteTransfer) {
                continue;
            }

            $itemTransfer->setProductConcrete($productConcreteTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function extractProductConcreteSkus(array $itemTransfers): array
    {
        $productConcreteSkus = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return array_unique($productConcreteSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function mapProductConcreteTransfersBySku(array $productConcreteTransfers): array
    {
        $mappedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $mappedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $mappedProductConcreteTransfers;
    }
}
