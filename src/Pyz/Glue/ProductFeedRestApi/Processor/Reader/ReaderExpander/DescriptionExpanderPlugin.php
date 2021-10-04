<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander;

use Pyz\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Store;

class DescriptionExpanderPlugin implements ReaderExpanderInterface
{
    protected const PRODUCTS = 'products';

    /**
     * @var \Pyz\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Pyz\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        Store $store
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->store = $store;
    }

    /**
     * @param array $catalogSearchResult
     *
     * @return array
     */
    public function expand(array $catalogSearchResult): array
    {
        $productAbstractIds = $this->getProductAbstractIds($catalogSearchResult[self::PRODUCTS]);

        $abstractProducts = $this
            ->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStoreWithoutRestrictions(
                $productAbstractIds,
                $this->store->getCurrentLocale(),
                $this->store->getStoreName()
            );

        foreach ($catalogSearchResult['products'] as &$singleProductResult) {
            $id = $singleProductResult['id_product_abstract'];
            $singleProductResult['description'] = $abstractProducts[$id]['description'];
        }

        return $catalogSearchResult;
    }

    /**
     * @param array $catalogSearchProducts
     *
     * @return array
     */
    protected function getProductAbstractIds(array $catalogSearchProducts): array
    {
        $productAbstractIds = [];
        foreach ($catalogSearchProducts as $singleProductResult) {
            $productAbstractIds[] = $singleProductResult['id_product_abstract'];
        }

        return $productAbstractIds;
    }
}
