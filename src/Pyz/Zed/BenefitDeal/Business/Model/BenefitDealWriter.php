<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class BenefitDealWriter implements BenefitDealWriterInterface
{
    /**
     * @var \Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface
     */
    private $entityManager;

    /**
     * @param \Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface $entityManager
     */
    public function __construct(BenefitDealEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderBenefitDealFromQuote(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void
    {
        if (!$this->assertBenefitDealApplied($quoteTransfer)) {
            return;
        }

        $salesOrderBenefitDealEntityTransfer = $this->mapSalesOrderBenefitDeal($quoteTransfer, $saveOrderTransfer->getIdSalesOrder());
        $this->entityManager->saveSalesOrderBenefitDeal($salesOrderBenefitDealEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer
     */
    private function mapSalesOrderBenefitDeal(QuoteTransfer $quoteTransfer, int $idSalesOrder): PyzSalesOrderBenefitDealEntityTransfer
    {
        $benefitDealEntityTransfer = new PyzSalesOrderBenefitDealEntityTransfer();
        $benefitDealEntityTransfer->setFkSalesOrder($idSalesOrder);
        $benefitDealEntityTransfer->setTotalShoppingPointsAmount($quoteTransfer->getTotalUsedShoppingPointsAmount());
        $benefitDealEntityTransfer->setTotalBenefitVouchersAmount($quoteTransfer->getTotalUsedBenefitVouchersAmount());

        return $benefitDealEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    private function assertBenefitDealApplied(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getTotalUsedBenefitVouchersAmount() > 0
            || $quoteTransfer->getTotalUsedShoppingPointsAmount() > 0;
    }
}
