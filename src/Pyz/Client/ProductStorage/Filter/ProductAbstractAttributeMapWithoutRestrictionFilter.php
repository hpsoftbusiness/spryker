<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage\Filter;

use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;

class ProductAbstractAttributeMapWithoutRestrictionFilter implements ProductAbstractAttributeMapRestrictionFilterInterface
{
    /**
     * @param array $productStorageData
     *
     * @return array
     */
    public function filterAbstractProductVariantsData(array $productStorageData): array
    {
        return $productStorageData;
    }
}
