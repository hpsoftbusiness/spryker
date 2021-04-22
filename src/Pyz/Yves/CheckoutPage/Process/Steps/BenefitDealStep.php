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

        $products = array_reduce(
            $dataTransfer->getItems()->getArrayCopy(),
            function (ArrayObject $carry, ItemTransfer $itemTransfer) {
                $benefitVoucherSalesData = $itemTransfer->getBenefitVoucherDealData();
                $currencyCode = $itemTransfer->getPriceProduct()->getMoneyValue()->getCurrency()->getCode();

                /**
                 * @TODO refactor properly after all payment branches are merged.
                 */
                if ($benefitVoucherSalesData && $benefitVoucherSalesData->getIsStore()) {
                    $carry[$itemTransfer->getIdProductAbstract()] = [
                        'prices' => $this->calculateSalesPriceForQuantity($itemTransfer),
                        'currencyCode' => $currencyCode,
                        'subTotalForItems' => number_format((float)($itemTransfer->getSumSubtotalAggregation() / 100), 2),
                        'unitPrice' => number_format((float)$itemTransfer->getUnitPrice() / 100, 2),
                    ];
                } elseif ($itemTransfer->getShoppingPointsDeal() && $itemTransfer->getShoppingPointsDeal()->getIsActive()) {
                    $carry[$itemTransfer->getIdProductAbstract()] = [
                        'prices' => [],
                        'currencyCode' => $currencyCode,
                        'unitPrice' => $itemTransfer->getOriginUnitGrossPrice(),
                    ];
                }

                return $carry;
            },
            new ArrayObject()
        );

        return [
            'benefitSalesInfo' => $products,
            'customerBalance' => [
                'benefitVouchersBalance' => $availableBalance->getAvailableBenefitVoucherAmount()->toFloat(),
                'benefitVouchersCurrencyCode' => $availableBalance->getAvailableBenefitVoucherCurrency(),
                'cashbackBalance' => $availableBalance->getAvailableCashbackAmount()->toFloat(),
                'cashbackCurrencyCode' => $availableBalance->getAvailableCashbackCurrency(),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array|null
     */
    protected function calculateSalesPriceForQuantity(ItemTransfer $itemTransfer): ?array
    {
        //TODO: implement functionality for make available choice amount of items to pay in one ItemTransfer
        $salesDataTransfer = $itemTransfer->getBenefitVoucherDealData();

        if ($salesDataTransfer && $salesDataTransfer->getIsStore()) {
            return [
                'salesPrice' => $salesDataTransfer->getSalesPrice() * $itemTransfer->getQuantity(),
                'benefitAmount' => $salesDataTransfer->getAmount() * $itemTransfer->getQuantity(),
                'quantity' => $itemTransfer->getQuantity(),
            ];
        }

        return null;
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
