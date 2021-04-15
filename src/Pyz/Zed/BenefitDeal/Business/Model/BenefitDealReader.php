<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface;

class BenefitDealReader implements BenefitDealReaderInterface
{
    /**
     * @var \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface
     */
    private $repository;

    /**
     * @param \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface $repository
     */
    public function __construct(BenefitDealRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithBenefitDeal(OrderTransfer $orderTransfer): OrderTransfer
    {
        if (!$orderTransfer->getIdSalesOrder()) {
            return $orderTransfer;
        }

        $benefitDealTransfer = $this->repository->findSalesOrderBenefitDealByIdSalesOrder($orderTransfer->getIdSalesOrder());
        if ($benefitDealTransfer) {
            $orderTransfer->setBenefitDeal($benefitDealTransfer);
        }

        return $orderTransfer;
    }
}
