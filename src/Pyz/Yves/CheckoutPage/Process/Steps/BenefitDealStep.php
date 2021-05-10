<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class BenefitDealStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    protected const STEP_TITLE = 'checkout.step.benefit_deal.title';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer): bool
    {
        return $this->assertCartHasApplicableBenefitDeals($dataTransfer);
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return static::STEP_TITLE;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer): bool
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
         */
        return $this->hasBenefitDealsApplied($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer): bool
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer): array
    {
        $availableBalance = $dataTransfer->getCustomer()->getCustomerBalance();

        return [
            'customerBalance' => [
                'benefitVouchersBalance' => $availableBalance->getAvailableBenefitVoucherAmount()->toFloat(),
                'benefitVouchersCurrencyCode' => $availableBalance->getAvailableBenefitVoucherCurrency(),
                'shoppingPointBalance' => $availableBalance->getAvailableShoppingPointAmount(),
                'cashbackBalance' => $availableBalance->getAvailableCashbackAmount()->toFloat(),
                'cashbackCurrencyCode' => $availableBalance->getAvailableCashbackCurrency(),
            ],
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertCartHasApplicableBenefitDeals(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getShoppingPointsDeal() && $item->getShoppingPointsDeal()->getIsActive()) {
                return true;
            }

            if ($item->getBenefitVoucherDealData() && $item->getBenefitVoucherDealData()->getIsStore()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasBenefitDealsApplied(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() || $itemTransfer->getUseShoppingPoints()) {
                return true;
            }
        }

        return false;
    }
}
