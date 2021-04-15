<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDealQuery;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDealQuery;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\BenefitDealMapper;
use Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\ItemBenefitDealMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface getRepository()
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class BenefitDealPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\BenefitDealMapper
     */
    public function createBenefitDealMapper(): BenefitDealMapper
    {
        return new BenefitDealMapper();
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper\ItemBenefitDealMapper
     */
    public function createItemBenefitDealMapper(): ItemBenefitDealMapper
    {
        return new ItemBenefitDealMapper();
    }

    /**
     * @return \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDealQuery
     */
    public function createPyzSalesOrderBenefitDealQuery(): PyzSalesOrderBenefitDealQuery
    {
        return PyzSalesOrderBenefitDealQuery::create();
    }

    /**
     * @return \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDealQuery
     */
    public function createPyzSalesOrderItemBenefitDealQuery(): PyzSalesOrderItemBenefitDealQuery
    {
        return PyzSalesOrderItemBenefitDealQuery::create();
    }
}
