<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
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
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface $salesProductConnectorRepository
     * @param \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SalesProductConnectorRepositoryInterface $salesProductConnectorRepository,
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService,
        LocaleFacadeInterface $localeFacade
    ) {
        $this->salesProductConnectorRepository = $salesProductConnectorRepository;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeFacade = $localeFacade;
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
        $mappedProductConcreteTransfers = $this->indexProductConcreteTransfersBySku($productConcreteTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteTransfer = $mappedProductConcreteTransfers[$itemTransfer->getSku()] ?? null;

            if (!$productConcreteTransfer) {
                continue;
            }

            $itemTransfer->setConcreteAttributes(
                array_merge(
                    $this->utilEncodingService->decodeJson($productConcreteTransfer->getAttributes(), true),
                    $this->extractLocalizedAttributesByCurrentLocaleCode($productConcreteTransfer)
                )
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array
     */
    protected function extractLocalizedAttributesByCurrentLocaleCode($productConcreteTransfer): array
    {
        $currentIdLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getIdLocale() === $currentIdLocale) {
                return $this->utilEncodingService->decodeJson($localizedAttribute->getAttributes(), true);
            }
        }

        return [];
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
    protected function indexProductConcreteTransfersBySku(array $productConcreteTransfers): array
    {
        $mappedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $mappedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $mappedProductConcreteTransfers;
    }
}
