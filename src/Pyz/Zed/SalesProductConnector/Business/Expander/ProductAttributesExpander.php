<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business\Expander;

use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class ProductAttributesExpander implements ProductAttributesExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface
     */
    protected $salesProductConnectorRepository;

    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface $salesProductConnectorRepository
     * @param \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        SalesProductConnectorRepositoryInterface $salesProductConnectorRepository,
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService
    ) {
        $this->salesProductConnectorRepository = $salesProductConnectorRepository;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductAttributes(array $itemTransfers): array
    {
        $productConcreteSkus = $this->extractProductConcreteSkus($itemTransfers);
        $productConcreteTransfers = $this->salesProductConnectorRepository->getRawProductConcreteTransfersByConcreteSkus($productConcreteSkus);
        $mappedProductConcreteTransfers = $this->mapProductConcreteTransfersBySku($productConcreteTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteTransfer = $mappedProductConcreteTransfers[$itemTransfer->getSku()] ?? null;

            if (!$productConcreteTransfer) {
                continue;
            }

            $itemTransfer->setConcreteAttributes($this->utilEncodingService->decodeJson($productConcreteTransfer->getAttributes(), true));
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
