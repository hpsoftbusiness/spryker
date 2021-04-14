<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;
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
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient
     * @param \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ContentProductWidgetToContentProductClientBridgeInterface $contentProductClient,
        ContentProductWidgetToProductStorageClientBridgeInterface $productStorageClient,
        Store $store
    ) {
        parent::__construct($contentProductClient, $productStorageClient);

        $this->store = $store;
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
        if ($productAbstractViewCollection) {
            $productAbstractViewCollection = $this->filterSellableAbstractProductViews($productAbstractViewCollection);
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
