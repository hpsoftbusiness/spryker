<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentNavigationWidget\Reader;

use Pyz\Client\Catalog\CatalogClientInterface;

class CategoryReader implements CategoryReaderInterface
{
    protected const KEY_FACETS = 'facets';
    protected const KEY_CATEGORY_FACET = 'category';

    /**
     * @var int[]
     */
    protected static $idCatalogToCatalogProductCountCache;

    /**
     * @var \Pyz\Client\Catalog\CatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @param \Pyz\Client\Catalog\CatalogClientInterface $catalogClient
     */
    public function __construct(CatalogClientInterface $catalogClient)
    {
        $this->catalogClient = $catalogClient;
    }

    /**
     * @param int $idCategory
     *
     * @return bool
     */
    public function getIsCatalogVisible(int $idCategory): bool
    {
        if (static::$idCatalogToCatalogProductCountCache === null) {
            static::$idCatalogToCatalogProductCountCache = $this->getCatalogVisibilityData();
        }

        return static::$idCatalogToCatalogProductCountCache[$idCategory] ?? false;
    }

    /**
     * @return int[]
     */
    protected function getCatalogVisibilityData(): array
    {
        $catalogVisibilitySearchResult = $this->catalogClient->getCatalogVisibility();

        if (!isset($catalogVisibilitySearchResult[static::KEY_FACETS][static::KEY_CATEGORY_FACET])) {
            return [];
        }

        $searchResultValueTransfers = $catalogVisibilitySearchResult[static::KEY_FACETS][static::KEY_CATEGORY_FACET]
            ->getValues();
        $catalogVisibilityData = [];

        foreach ($searchResultValueTransfers as $searchResultValueTransfer) {
            $catalogVisibilityData[$searchResultValueTransfer->getValue()] = $searchResultValueTransfer->getDocCount();
        }

        return $catalogVisibilityData;
    }
}
