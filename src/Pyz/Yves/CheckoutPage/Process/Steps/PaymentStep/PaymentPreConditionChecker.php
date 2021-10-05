<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PaymentPreConditionChecker
 *
 * @package Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep
 */
class PaymentPreConditionChecker implements PreConditionCheckerInterface
{
    private const ERROR_NOT_ENOUGH_SHOPPING_POINTS = 'checkout.step.benefit_deal.error.not_enough_shopping_points_balance';

    private const ERROR_NOT_ENOUGH_BENEFIT_VOUCHER_BALANCE = 'checkout.step.benefit_deal.error.not_enough_benefit_voucher_balance';

    private const ERROR_MIN_AMOUNT_BENEFIT_VOUCHER_TO_SPENT = 'checkout.step.benefit_deal.error.not_spent_min_voucher_balance';

    private const ERROR_AMOUNT_BENEFIT_VOUCHER_USED_BIGGER_THEN_IN_ITEMS = 'checkout.step.benefit_deal.error.amount_is_bigger_then_in_items';

    private const MIN_VOUCHER_AMOUNT_TO_SPENT_WHEN_HAS_VOUCHER = 1;

    /**
     * @var \Pyz\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface
     */
    private $localeClient;

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
     * @var \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    private $integerToDecimalConverter;

    /**
     * @param \Pyz\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface $localeClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     * @param \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(
        CheckoutPageToLocaleClientInterface $localeClient,
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator,
        CustomerServiceInterface $customerService,
        IntegerToDecimalConverterInterface $integerToDecimalConverter
    ) {
        $this->localeClient = $localeClient;
        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
        $this->customerService = $customerService;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        $benefitVoucherItemsAmount = $this->getBenefitVoucherItemsAmount($quoteTransfer);

        if (!$this->assertShoppingPointsBalance($quoteTransfer, $customerTransfer)) {
            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_SHOPPING_POINTS);

            return false;
        }

        $customerBenefitVoucherBalance = $this->customerService->getCustomerBenefitVoucherBalanceAmount($customerTransfer);
        if (!$this->assertBenefitVoucherBalance($quoteTransfer, $customerBenefitVoucherBalance)) {
            $neededAmount = $this->integerToDecimalConverter->convert($quoteTransfer->getTotalUsedBenefitVouchersAmount());
            $balanceAmount = $this->integerToDecimalConverter->convert($customerBenefitVoucherBalance);

            $neededAmountFormatted = $this->localeClient->formatNumberDueToCountry($neededAmount);
            $balanceAmountFormatted = $this->localeClient->formatNumberDueToCountry($balanceAmount);

            $this->addErrorMessage(self::ERROR_NOT_ENOUGH_BENEFIT_VOUCHER_BALANCE, [
                '%needAmount%' => $neededAmountFormatted,
                '%balanceAmount%' => $balanceAmountFormatted,
                '%currency%' => $quoteTransfer->getCurrency()->getCode(),
            ]);

            return false;
        }

        if (!$this->assertVoucherBalanceForItems($quoteTransfer, $benefitVoucherItemsAmount)) {
            $this->addErrorMessage(self::ERROR_AMOUNT_BENEFIT_VOUCHER_USED_BIGGER_THEN_IN_ITEMS, [
                '%maxAmount%' => $this->integerToDecimalConverter->convert($this->getBenefitVoucherItemsAmount($quoteTransfer)),
                '%currency%' => $quoteTransfer->getCurrency()->getCode(),
            ]);

            return false;
        }

        if (!$this->hasSpentMinVoucherBalance($quoteTransfer, $customerBenefitVoucherBalance, $benefitVoucherItemsAmount)) {
            $this->addErrorMessage(self::ERROR_MIN_AMOUNT_BENEFIT_VOUCHER_TO_SPENT, [
                '%needAmount%' => $this->integerToDecimalConverter->convert(self::MIN_VOUCHER_AMOUNT_TO_SPENT_WHEN_HAS_VOUCHER),
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
    private function assertShoppingPointsBalance(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        $totalUsedShoppingPointsSum = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shoppingPointsDeal = $itemTransfer->getShoppingPointsDeal();

            if ($itemTransfer->getUseShoppingPoints() && $shoppingPointsDeal) {
                $totalUsedShoppingPointsSum += ($shoppingPointsDeal->getShoppingPointsQuantity() * $itemTransfer->getQuantity());
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
     * @param int $benefitVoucherBalance
     *
     * @return bool
     */
    private function assertBenefitVoucherBalance(QuoteTransfer $quoteTransfer, int $benefitVoucherBalance): bool
    {
        if (!$this->hasBenefitDealsApplied($quoteTransfer)) {
            return true;
        }

        return $quoteTransfer->getTotalUsedBenefitVouchersAmount() <= $benefitVoucherBalance;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $benefitVoucherItemsAmount
     *
     * @return bool
     */
    private function assertVoucherBalanceForItems(QuoteTransfer $quoteTransfer, int $benefitVoucherItemsAmount): bool
    {
        if (!$this->hasBenefitProductInCart($benefitVoucherItemsAmount)) {
            return true;
        }

        return $quoteTransfer->getTotalUsedBenefitVouchersAmount() <= $benefitVoucherItemsAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasBenefitDealsApplied(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getUseBenefitVoucher() && $quoteTransfer->getTotalUsedBenefitVouchersAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $benefitVoucherBalance
     * @param int $benefitVoucherItemsAmount
     *
     * @return bool
     */
    private function hasSpentMinVoucherBalance(QuoteTransfer $quoteTransfer, int $benefitVoucherBalance, int $benefitVoucherItemsAmount): bool
    {
        if (!$this->hasBenefitProductInCart($benefitVoucherItemsAmount)) {
            return true;
        }

        return $quoteTransfer->getTotalUsedBenefitVouchersAmount() >= self::MIN_VOUCHER_AMOUNT_TO_SPENT_WHEN_HAS_VOUCHER && $benefitVoucherBalance > 0;
    }

    /**
     * @param int $benefitVoucherItemsAmount
     *
     * @return bool
     */
    protected function hasBenefitProductInCart(int $benefitVoucherItemsAmount): bool
    {
        return $benefitVoucherItemsAmount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    private function getBenefitVoucherItemsAmount(QuoteTransfer $quoteTransfer): int
    {
        $amount = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $benefitVoucherDealData = $itemTransfer->getBenefitVoucherDealData();
            if ($benefitVoucherDealData && $benefitVoucherDealData->getAmount()) {
                $amount += $benefitVoucherDealData->getAmount() * $itemTransfer->getQuantity();
            }
        }

        return $amount;
    }
}
