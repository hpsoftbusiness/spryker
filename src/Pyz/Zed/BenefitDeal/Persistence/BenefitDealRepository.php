<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\BenefitDealMapper;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\ItemBenefitDealMapper;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealPersistenceFactory getFactory()
 */
class BenefitDealRepository extends AbstractRepository implements BenefitDealRepositoryInterface
{
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
