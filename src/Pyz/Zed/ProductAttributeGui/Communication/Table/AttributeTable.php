<?php declare(strict_types=1);

namespace Pyz\Zed\ProductAttributeGui\Communication\Table;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductAttributeGui\Communication\Table\AttributeTable as SprykerAttributeTable;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class AttributeTable extends SprykerAttributeTable
{
    /**
     * @var \Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface
     */
    private $productReader;

    /**
     * AttributeTable constructor.
     *
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer
     * @param \Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface $productReader
     */
    public function __construct(
        ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        ProductReaderInterface $productReader
    ) {
        parent::__construct($productAttributeQueryContainer);
        $this->productReader = $productReader;
    }

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

        if ($this->isProductCanBeDeleted($item['spy_product_attribute_key.key'])) {
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
     * @return bool
     */
    protected function isProductCanBeDeleted(string $attributeKey): bool
    {
        return !$this->isThisAttributeUsingByProduct($attributeKey)
        && !$this->isThisAttributeUsingByAbstractProduct($attributeKey);
    }

    /**
     * @param string $attributeKey
     *
     * @return bool
     */
    protected function isThisAttributeUsingByAbstractProduct(string $attributeKey): bool
    {
        return $this->productReader->getCountAbstractProductUsingAttribute($attributeKey) > 0;
    }

    /**
     * @param string $attributeKey
     *
     * @return bool
     */
    protected function isThisAttributeUsingByProduct(string $attributeKey): bool
    {
        return $this->productReader->getCountProductUsingAttribute($attributeKey) > 0;
    }
}
