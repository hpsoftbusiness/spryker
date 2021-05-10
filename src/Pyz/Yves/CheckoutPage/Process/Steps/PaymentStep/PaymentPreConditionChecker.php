<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;

use Generated\Shared\Transfer\CustomerBalanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentPreConditionChecker implements PreConditionCheckerInterface
{
    private const ERROR_NOT_ENOUGH_SHOPPING_POINTS = 'checkout.step.benefit_deal.error.not_enough_shopping_points_balance';
    private const ERROR_NOT_ENOUGH_BENEFIT_VOUCHER_BALANCE = 'checkout.step.benefit_deal.error.not_enough_benefit_voucher_balance';

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    private $flashMessenger;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator
    ) {
        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        $customerBalanceTransfer = $quoteTransfer->getCustomer()->getCustomerBalance();
        if (!$this->assertShoppingPointsBalance($quoteTransfer, $customerBalanceTransfer)) {
            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_SHOPPING_POINTS);

            return false;
        }

        if (!$this->assertBenefitVoucherBalance($quoteTransfer, $customerBalanceTransfer)) {
            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_BENEFIT_VOUCHER_BALANCE, [
                '%needAmount%' => $this->getCommonSelectedBenefitAmount($quoteTransfer),
                '%balanceAmount%' => $quoteTransfer->getCustomer()->getCustomerBalance()->getAvailableBenefitVoucherAmount()->toFloat(),
                '%currency%' => $quoteTransfer->getCustomer()->getCustomerBalance()->getAvailableBenefitVoucherCurrency(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerBalanceTransfer $balanceTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherBalance(QuoteTransfer $quoteTransfer, CustomerBalanceTransfer $balanceTransfer): bool
    {
        if (!$this->hasBenefitDealsApplied($quoteTransfer)) {
            return true;
        }

        $commonSelectedBenefitVouchers = $this->getCommonSelectedBenefitAmount($quoteTransfer);
        $clientBalanceBenefitVouchers = $balanceTransfer->getAvailableBenefitVoucherAmount();

        return $clientBalanceBenefitVouchers->greatherThanOrEquals($commonSelectedBenefitVouchers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerBalanceTransfer $balanceTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsBalance(QuoteTransfer $quoteTransfer, CustomerBalanceTransfer $balanceTransfer): bool
    {
        $totalUsedShoppingPointsSum = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $chargeAmountData = $itemTransfer->getBenefitDealChargeAmountData();

            if ($itemTransfer->getUseShoppingPoints() && $chargeAmountData) {
                $totalUsedShoppingPointsSum += $chargeAmountData->getTotalShoppingPointsAmount();
            }
        }

        return $totalUsedShoppingPointsSum <= $balanceTransfer->getAvailableShoppingPointAmount()->toFloat();
    }

    /**
     * @param string $messageTranslationKey
     * @param array $params
     *
     * @return void
     */
    private function addErrorMessage(string $messageTranslationKey, array $params = []): void
    {
        $translatedMessage = $this->translator->trans($messageTranslationKey, $params);
        $this->flashMessenger->addErrorMessage($translatedMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getCommonSelectedBenefitAmount(QuoteTransfer $quoteTransfer): int
    {
        $commonSelectedBenefitVouchers = 0;

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $chargeAmountData = $itemTransfer->getBenefitDealChargeAmountData();

            if ($itemTransfer->getUseBenefitVoucher() && $chargeAmountData) {
                $commonSelectedBenefitVouchers += $chargeAmountData->getTotalBenefitVouchersAmount();
            }
        }

        return $commonSelectedBenefitVouchers;
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
