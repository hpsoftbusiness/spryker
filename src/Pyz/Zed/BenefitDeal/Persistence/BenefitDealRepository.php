<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Orm\Zed\ProductAbstractAttribute\Persistence\Map\PyzProductAbstractAttributeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
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
