<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander;

use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface;
use Spryker\Shared\Kernel\Store;

class CategoryExpanderPlugin implements ReaderExpanderInterface
{
    protected const PRODUCTS = 'products';

    /**
     * @var \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface
     */
    protected $productCategoryStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface $productCategoryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ProductCategoryStorageClientInterface $productCategoryStorageClient,
        Store $store
    ) {
        $this->productCategoryStorageClient = $productCategoryStorageClient;
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

        $productAbstractCategoriesResult = $this
            ->productCategoryStorageClient
            ->findBulkProductAbstractCategory(
                $productAbstractIds,
                $this->store->getCurrentLocale()
            );

        $productAbstractCategories = [];
        foreach ($productAbstractCategoriesResult as $category) {
            $productAbstractCategories[$category->getIdProductAbstract()] = $category->getCategories()->getArrayCopy();
        }

        foreach ($catalogSearchResult[self::PRODUCTS] as &$singleProductResult) {
            $id = $singleProductResult['id_product_abstract'];
            /** @var \Generated\Shared\Transfer\ProductCategoryStorageTransfer $lastCategory */
            $lastCategory = end($productAbstractCategories[$id]);
            $singleProductResult['category'] = $lastCategory ? $lastCategory->getName() : null;
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
