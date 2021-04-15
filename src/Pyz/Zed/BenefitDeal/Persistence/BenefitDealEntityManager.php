<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealPersistenceFactory getFactory()
 */
class BenefitDealEntityManager extends AbstractEntityManager implements BenefitDealEntityManagerInterface
{
    /**
     * @return void
     */
    public function saveSalesOrderBenefitDeal(PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer): void
    {
        $benefitDealEntity = $this->getFactory()->createBenefitDealMapper()
            ->mapEntityTransferToEntity($benefitDealEntityTransfer, new PyzSalesOrderBenefitDeal());

        $benefitDealEntity->save();
    }
}
