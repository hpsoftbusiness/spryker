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
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer as SprykerProductAttributeQueryContainer;

class ProductAttributeQueryContainer extends SprykerProductAttributeQueryContainer
{
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
}
