<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog;

use Spryker\Client\Catalog\CatalogClientInterface as SprykerCatalogClientInterface;

interface CatalogClientInterface extends SprykerCatalogClientInterface
{
    /**
     * Specification:
     * - Gets the category list with the number of products visible in each category.
     *
     * @api
     *
     * @param mixed[] $requestParameters
     *
     * @return mixed[]
     */
    public function getCatalogVisibility(array $requestParameters = []): array;
}
