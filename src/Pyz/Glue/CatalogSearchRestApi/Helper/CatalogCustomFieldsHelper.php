<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Helper;

class CatalogCustomFieldsHelper implements CatalogCustomFieldsHelperInterface
{
    public const KEY_CASHBACK_AMOUNT = 'cashback_amount';
    public const KEY_SHOPPING_POINTS = 'shopping_points';
    public const KEY_ATTRIBUTES = 'attributes';
    public const KEY_BENEFIT_STORE = 'benefit_store';
    public const KEY_SHOPPING_POINT_STORE = 'shopping_point_store';

    /**
     * @param mixed $data
     *
     * @return int
     */
    public function prepareCustomArrayData($data): int
    {
        if (is_array($data)) {
            if (empty($data)) {
                return 0;
            }

            return (int)max($data);
        }

        return (int)$data;
    }
}
