<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Persistence;

use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer as SprykerProductAttributeQueryContainer;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface as SpyProductAttributeGuiToProductAttributeQueryContainerInterface;

class ProductAttributeQueryContainer extends SprykerProductAttributeQueryContainer implements SpyProductAttributeGuiToProductAttributeQueryContainerInterface, ProductAttributeGuiToProductAttributeQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $keys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKeys($keys)
    {
        return $this->getFactory()
            ->createProductAttributeKeyQuery()
            ->filterByKey_In($keys);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int|null $offset
     * @param int $limit
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueWithTranslation(
        $idProductManagementAttribute,
        $idLocale,
        $searchText = '',
        $offset = null,
        $limit = 10
    ): SpyProductManagementAttributeValueQuery {
        $query = $this->getFactory()
            ->createProductManagementAttributeValueQuery()
            ->filterByFkProductManagementAttribute($idProductManagementAttribute)
            ->addJoin([
                SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                (int)$idLocale,
            ], [
                SpyProductManagementAttributeValueTranslationTableMap::COL_FK_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE,
                SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE,
            ], Criteria::LEFT_JOIN)
            ->clearSelectColumns()
            ->withColumn((string)$idLocale, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation');

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductManagementAttributeValueTableMap::COL_VALUE . ') LIKE ?', $term, PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        if ($offset !== null) {
            $query->setOffset($offset);
        }

        if ($limit) {
            $query->setLimit($limit);
        }

        return $query;
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return void
     */
    public function deleteProductAttributeValuesWithTranslations(int $idProductManagementAttribute): void
    {
        $productAttributeValues = $this->getFactory()
            ->createProductManagementAttributeValueQuery()
            ->findByFkProductManagementAttribute($idProductManagementAttribute);

        foreach ($productAttributeValues as $productAttributeValue) {
            $this->deleteProductAttributeValuesTranslations($productAttributeValue->getIdProductManagementAttributeValue());

            $productAttributeValue->delete();
        }
    }

    /**
     * @param int $idProductManagementAttributeValue
     *
     * @return void
     */
    public function deleteProductAttributeValuesTranslations(int $idProductManagementAttributeValue): void
    {
        $this->getFactory()
            ->createProductManagementAttributeValueTranslationQuery()
            ->findByFkProductManagementAttributeValue($idProductManagementAttributeValue)
            ->delete();
    }

    /**
     * @param string $attributeKey
     *
     * @return void
     */
    public function deleteProductAttributeKeyByKey(string $attributeKey): void
    {
        $this->getFactory()
            ->createProductAttributeKeyQuery()
            ->findByKey($attributeKey)
            ->delete();
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return void
     */
    public function deleteProductAttribute(int $idProductManagementAttribute): void
    {
        $productAttribute = $this->getFactory()
            ->createProductManagementAttributeQuery()
            ->findOneByIdProductManagementAttribute($idProductManagementAttribute);

        if ($productAttribute) {
            $key = $productAttribute->getSpyProductAttributeKey()->getKey();

            $this->deleteProductAttributeValuesWithTranslations($idProductManagementAttribute);
            $productAttribute->delete();
            $this->deleteProductAttributeKeyByKey($key);
        }
    }
}
