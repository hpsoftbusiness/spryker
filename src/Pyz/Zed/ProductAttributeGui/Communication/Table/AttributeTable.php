<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductAttributeGui\Communication\Table\AttributeTable as SprykerAttributeTable;

class AttributeTable extends SprykerAttributeTable
{
    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-attribute-gui/attribute/view', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'View'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-attribute-gui/attribute/edit', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'Edit'
        );

        $urls[] = $this->generateRemoveButton(
            Url::generate('/product-attribute-gui/attribute/delete', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'Delete'
        );

        return $urls;
    }
}
