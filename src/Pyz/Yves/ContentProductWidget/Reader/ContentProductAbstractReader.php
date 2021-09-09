<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;
use Pyz\Yves\ContentProductWidget\ContentProductWidgetConfig;
use Spryker\Client\ProductListStorage\ProductListStorageClientInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReader as SprykerContentProductAbstractReader;

class ContentProductAbstractReader extends SprykerContentProductAbstractReader
{
    private const ATTRIBUTE_SELLABLE_PATTERN = 'sellable_%s';

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @var \Pyz\Yves\ContentProductWidget\ContentProductWidgetConfig
     */
    private $config;

    /**
     * @var \Spryker\Client\ProductListStorage\ProductListStorageClientInterface
     */
    private $productListStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Pyz\Yves\ContentProductWidget\ContentProductWidgetConfig $config
     * @param \Spryker\Client\ProductListStorage\ProductListStorageClientInterface $productListStorageClient
     */
    public function __construct(
        ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient,
        ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient,
        Store $store,
        ContentProductWidgetConfig $config,
        ProductListStorageClientInterface $productListStorageClient
    ) {
        parent::__construct($contentProductClient, $productStorageClient);

        $this->store = $store;
        $this->config = $config;
        $this->productListStorageClient = $productListStorageClient;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]|null
     */
    public function findProductAbstractCollection(string $contentKey, string $localeName): ?array
    {
        $productAbstractViewCollection = parent::findProductAbstractCollection($contentKey, $localeName);

        $filteredProductAbstractViewCollection = $this->filterByRestrictedProducts($productAbstractViewCollection);

        if ($this->config->isMultiCountryEnabled() && $filteredProductAbstractViewCollection) {
            $filteredProductAbstractViewCollection = $this->filterSellableAbstractProductViews($filteredProductAbstractViewCollection);
        }

        return $filteredProductAbstractViewCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[]|null $productAbstractViewCollection
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    private function filterByRestrictedProducts(?array $productAbstractViewCollection): array
    {
        foreach ($productAbstractViewCollection as $key => $item) {
            if ($this->productListStorageClient->isProductAbstractRestricted($item->getIdProductAbstract())) {
                unset($productAbstractViewCollection[$key]);
            }
        }

        return $productAbstractViewCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productAbstractViewCollection
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    private function filterSellableAbstractProductViews(array $productAbstractViewCollection): array
    {
        $sellableAttributeKey = $this->getSellableAttributeKey();

        return array_filter(
            $productAbstractViewCollection,
            static function (ProductViewTransfer $productViewTransfer) use ($sellableAttributeKey): bool {
                if (!array_key_exists($sellableAttributeKey, $productViewTransfer->getAttributes())) {
                    return false;
                }

                return (bool)$productViewTransfer->getAttributes()[$sellableAttributeKey];
            }
        );
    }

    /**
     * @return string
     */
    private function getSellableAttributeKey(): string
    {
        return sprintf(
            self::ATTRIBUTE_SELLABLE_PATTERN,
            strtolower($this->store->getCurrentCountry())
        );
    }
}
