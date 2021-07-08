<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductAbstractAttribute\Persistence\Map\PyzProductAbstractAttributeTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\BasicModelCriterion;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\BenefitDealMapper;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\ItemBenefitDealMapper;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealPersistenceFactory getFactory()
 */
class BenefitDealRepository extends AbstractRepository implements BenefitDealRepositoryInterface
{
    protected const BENEFIT_STORE_VALUE_ACTIVE = 1;
    protected const BENEFIT_STORE_VALUE_INACTIVE = 0;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer|null
     */
    public function findSalesOrderBenefitDealByIdSalesOrder(int $idSalesOrder): ?PyzSalesOrderBenefitDealEntityTransfer
    {
        $benefitDealEntity = $this->getFactory()->createPyzSalesOrderBenefitDealQuery()
            ->findOneByFkSalesOrder($idSalesOrder);
        if (!$benefitDealEntity) {
            return null;
        }

        return $this->getBenefitDealMapper()->mapEntityToTransferEntity(
            $benefitDealEntity,
            new PyzSalesOrderBenefitDealEntityTransfer()
        );
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer[]
     */
    public function findSalesOrderItemBenefitDealsByIdSalesOrderItem(int $idSalesOrderItem): array
    {
        $salesOrderItemBenefitDealEntities = $this->getFactory()->createPyzSalesOrderItemBenefitDealQuery()
            ->findByFkSalesOrderItem($idSalesOrderItem);

        if (empty($salesOrderItemBenefitDealEntities)) {
            return [];
        }

        return $this->getItemBenefitDealMapper()
            ->mapEntityCollectionToTransfers($salesOrderItemBenefitDealEntities->getData());
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function findProductAbstractIdsBecomingInactiveByBenefitProductLabelId(int $idProductLabel): array
    {
        return $this->getFactory()
            ->createProductAbstractAttributeQuery()
            ->select([PyzProductAbstractAttributeTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByBenefitStore(static::BENEFIT_STORE_VALUE_INACTIVE)
            ->useSpyProductAbstractQuery()
                ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                    ->filterByFkProductLabel($idProductLabel)
                ->endUse()
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function findProductAbstractIdsBecomingActiveByBenefitProductLabelId(int $idProductLabel): array
    {
        return $this->getFactory()
            ->createProductAbstractAttributeQuery()
            ->select([PyzProductAbstractAttributeTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByBenefitStore(true)
            ->useSpyProductAbstractQuery()
                ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                    ->filterByFkProductLabel(null, Criteria::ISNULL)
                ->endUse()
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel))
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function findProductAbstractIdsBecomingInactiveByShoppingPointProductLabelId(int $idProductLabel): array
    {
        return $this->getFactory()
            ->createProductAbstractAttributeQuery()
            ->select([PyzProductAbstractAttributeTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByShoppingPoint(false)
            ->useSpyProductAbstractQuery()
                ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                    ->filterByFkProductLabel($idProductLabel)
                ->endUse()
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function findProductAbstractIdsBecomingActiveByShoppingPointProductLabelId(int $idProductLabel): array
    {
        return $this->getFactory()
            ->createProductAbstractAttributeQuery()
            ->select([PyzProductAbstractAttributeTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByShoppingPoint(true)
            ->useSpyProductAbstractQuery()
                ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                    ->filterByFkProductLabel(null, Criteria::ISNULL)
                ->endUse()
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel))
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function findProductAbstractIdsBecomingActiveByInsteadOfProductLabelId(int $idProductLabel): array
    {
        $productAbstractQuery = $this
            ->getFactory()
            ->createProductAbstractQuery()
            ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->distinct();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductOrigin', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeOrigin', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeOrigin',
                    'priceTypeOrigin.name = ?',
                    'ORIGINAL'
                )
                ->usePriceProductStoreQuery('priceProductStoreOrigin', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultOriginal', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductBenefit', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeBenefit', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeBenefit',
                    'priceTypeBenefit.name = ?',
                    'BENEFIT'
                )
                ->usePriceProductStoreQuery('priceProductStoreBenefit', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultBenefit', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductDefault', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeDefault', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeDefault',
                    'priceTypeDefault.name = ?',
                    'DEFAULT'
                )
                ->usePriceProductStoreQuery('priceProductStoreDefault', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultDefault', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        $productAbstractQuery = $productAbstractQuery
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition(
                'rel',
                sprintf('rel.fk_product_label = %d', $idProductLabel)
            )
            ->addAnd('rel.fk_product_label', null, Criteria::ISNULL)
            ->addAnd(
                'priceProductDefaultOriginal.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductDefaultDefault.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductDefaultBenefit.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductStoreOrigin.gross_price',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductStoreBenefit.gross_price',
                null,
                Criteria::ISNOTNULL
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreOrigin.fk_store = priceProductStoreDefault.fk_store'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreBenefit.fk_store = priceProductStoreDefault.fk_store'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreOrigin.fk_currency = priceProductStoreDefault.fk_currency'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreBenefit.fk_currency = priceProductStoreDefault.fk_currency'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreOrigin.gross_price > priceProductStoreDefault.gross_price'
            );

        return $productAbstractQuery
                ->find()
                ->toArray();
    }

    /**
     * @param int $idProductLabel
     *
     * @return array
     */
    public function findProductAbstractIdsBecomingInactiveByInsteadOfProductLabelId(int $idProductLabel): array
    {
        $productLabelProductAbstractQuery = $this
            ->getFactory()
            ->createProductLabelProductAbstractQuery()
            ->filterByFkProductLabel($idProductLabel)
            ->select(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->distinct();

        $productAbstractQuery = $productLabelProductAbstractQuery
            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN);

        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductOrigin', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeOrigin', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeOrigin',
                    'priceTypeOrigin.name = ?',
                    'ORIGINAL'
                )
                ->usePriceProductStoreQuery('priceProductStoreOrigin', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultOriginal', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductBenefit', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeBenefit', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeBenefit',
                    'priceTypeBenefit.name = ?',
                    'BENEFIT'
                )
                ->usePriceProductStoreQuery('priceProductStoreBenefit', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultBenefit', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery */
        $productAbstractQuery = $productAbstractQuery
            ->usePriceProductQuery('priceProductDefault', Criteria::LEFT_JOIN)
                ->joinPriceType('priceTypeDefault', Criteria::INNER_JOIN)
                ->addJoinCondition(
                    'priceTypeDefault',
                    'priceTypeDefault.name = ?',
                    'DEFAULT'
                )
                ->usePriceProductStoreQuery('priceProductStoreDefault', Criteria::LEFT_JOIN)
                    ->usePriceProductDefaultQuery('priceProductDefaultDefault', Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse();

        $productLabelProductAbstractQuery = $productAbstractQuery->endUse();

        $productLabelProductAbstractQuery = $productLabelProductAbstractQuery
            ->addAnd(
                'priceProductDefaultOriginal.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductDefaultBenefit.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addAnd(
                'priceProductDefaultDefault.id_price_product_default',
                null,
                Criteria::ISNOTNULL
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreOrigin.fk_store = priceProductStoreDefault.fk_store'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreOrigin.fk_currency = priceProductStoreDefault.fk_currency'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreBenefit.fk_store = priceProductStoreDefault.fk_store'
            )
            ->addJoinCondition(
                'priceProductStoreDefault',
                'priceProductStoreBenefit.fk_currency = priceProductStoreDefault.fk_currency'
            );

        $orCriterion = $this->getBasicModelCriterion(
            $productLabelProductAbstractQuery,
            'priceProductStoreOrigin.gross_price < priceProductStoreDefault.gross_price',
            'priceProductStoreOrigin.gross_price'
        );
        $orCriterion->addOr(
            $productLabelProductAbstractQuery
                ->getNewCriterion(
                    'priceProductStoreOrigin.gross_price',
                    null,
                    Criteria::ISNULL
                )
        );
        $orCriterion->addOr(
            $productLabelProductAbstractQuery
                ->getNewCriterion(
                    'priceProductStoreBenefit.gross_price',
                    null,
                    Criteria::ISNULL
                )
        );
        $orCriterion->addOr(
            $productLabelProductAbstractQuery
                ->getNewCriterion(
                    'priceProductStoreDefault.gross_price',
                    null,
                    Criteria::ISNULL
                )
        );

        return $productLabelProductAbstractQuery
            ->addAnd($orCriterion)
            ->find()
            ->toArray();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $clause
     * @param \Propel\Runtime\Map\ColumnMap|string $column
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\BasicModelCriterion
     */
    protected function getBasicModelCriterion(Criteria $criteria, string $clause, $column): BasicModelCriterion
    {
        return new BasicModelCriterion($criteria, $clause, $column);
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\BenefitDealMapper
     */
    private function getBenefitDealMapper(): BenefitDealMapper
    {
        return $this->getFactory()->createBenefitDealMapper();
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\ItemBenefitDealMapper
     */
    private function getItemBenefitDealMapper(): ItemBenefitDealMapper
    {
        return $this->getFactory()->createItemBenefitDealMapper();
    }
}
