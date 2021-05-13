<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
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
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator,
        CustomerServiceInterface $customerService
    ) {
        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
        $this->customerService = $customerService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        if (!$this->assertShoppingPointsBalance($quoteTransfer, $customerTransfer)) {
            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_SHOPPING_POINTS);

            return false;
        }

        if (!$this->assertBenefitVoucherBalance($quoteTransfer, $customerTransfer)) {
            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_BENEFIT_VOUCHER_BALANCE, [
                '%needAmount%' => $this->getCommonSelectedBenefitAmount($quoteTransfer),
                '%balanceAmount%' => $this->customerService->getCustomerBenefitVoucherBalanceAmount($customerTransfer),
                '%currency%' => $quoteTransfer->getCurrency()->getCode(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherBalance(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (!$this->hasBenefitDealsApplied($quoteTransfer)) {
            return true;
        }

        $commonSelectedBenefitVouchers = $this->getCommonSelectedBenefitAmount($quoteTransfer);
        $benefitVoucherBalance = $this->customerService->getCustomerBenefitVoucherBalanceAmount($customerTransfer);

        return $commonSelectedBenefitVouchers <= $benefitVoucherBalance;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsBalance(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        $totalUsedShoppingPointsSum = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $chargeAmountData = $itemTransfer->getBenefitDealChargeAmountData();

            if ($itemTransfer->getUseShoppingPoints() && $chargeAmountData) {
                $totalUsedShoppingPointsSum += $chargeAmountData->getTotalShoppingPointsAmount();
            }
        }

        $shoppingPointsBalance = $this->customerService->getCustomerShoppingPointsBalanceAmount($customerTransfer);

        return $totalUsedShoppingPointsSum <= $shoppingPointsBalance;
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
