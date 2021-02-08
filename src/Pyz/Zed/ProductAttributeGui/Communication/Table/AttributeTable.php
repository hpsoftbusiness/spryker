<?php declare(strict_types=1);

namespace Pyz\Zed\ProductAttributeGui\Communication\Table;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductAttributeGui\Communication\Table\AttributeTable as SprykerAttributeTable;

class AttributeTable extends SprykerAttributeTable
{
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

        if (!$this->getOneProductUsingThisAttribute($item['spy_product_attribute_key.key'])
            && !$this->getOneAbstractProductUsingThisAttribute($item['spy_product_attribute_key.key'])) {
            $urls[] = $this->generateRemoveButton(
                Url::generate('/product-attribute-gui/attribute/delete', [
                    'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
                ]),
                'Delete'
            );
        }

        return $urls;
    }

    /**
     * @param string $attributeKey
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    protected function getOneAbstractProductUsingThisAttribute(string $attributeKey)
    {
        return SpyProductAbstractQuery::create()
            ->select('id_product_abstract')
            ->where('attributes like "%\"' . $attributeKey . '\"%"')
            ->findOne();
    }

    /**
     * @param string $attributeKey
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    protected function getOneProductUsingThisAttribute(string $attributeKey)
    {
        return SpyProductQuery::create()
            ->select('id_product')
            ->where('attributes like "%\"' . $attributeKey . '\"%"')
            ->findOne();
    }
}
