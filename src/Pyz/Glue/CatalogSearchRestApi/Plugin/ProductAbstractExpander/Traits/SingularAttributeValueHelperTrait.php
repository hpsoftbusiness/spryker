<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits;

trait SingularAttributeValueHelperTrait
{
    /**
     * @param mixed $attributeValue
     *
     * @return mixed
     */
    protected function extractSingularAttributeValue($attributeValue)
    {
        if (!is_array($attributeValue)) {
            return $attributeValue;
        }

        if (!count($attributeValue)) {
            return null;
        }

        return current($attributeValue);
    }
}
