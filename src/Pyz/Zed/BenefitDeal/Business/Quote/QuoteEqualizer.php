<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteEqualizer implements QuoteEqualizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function equalize(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $sourceCartItemsBenefitDealsUsageMap = $this->createCartItemsBenefitDealsUsageMap($sourceQuoteTransfer->getItems());
        $this->recoverBenefitUsageFlags($resultQuoteTransfer, $sourceCartItemsBenefitDealsUsageMap);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param array $benefitDealsUsageMap
     *
     * @return void
     */
    private function recoverBenefitUsageFlags(QuoteTransfer $resultQuoteTransfer, array $benefitDealsUsageMap): void
    {
        foreach ($resultQuoteTransfer->getItems() as $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            $itemBenefitDealsUsageMap = $benefitDealsUsageMap[$itemIdentifier] ?? null;
            if (!$itemBenefitDealsUsageMap) {
                continue;
            }

            $itemTransfer->setUseBenefitVoucher($itemBenefitDealsUsageMap[ItemTransfer::USE_BENEFIT_VOUCHER] ?? null);
            $itemTransfer->setUseShoppingPoints($itemBenefitDealsUsageMap[ItemTransfer::USE_SHOPPING_POINTS] ?? null);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    private function getItemIdentifier(ItemTransfer $itemTransfer): string
    {
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     *
     * @return array
     */
    private function createCartItemsBenefitDealsUsageMap(ArrayObject $cartItems)
    {
        $cartIndex = [];
        foreach ($cartItems as $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer);
            $cartIndex[$itemIdentifier] = [
                ItemTransfer::USE_BENEFIT_VOUCHER => $itemTransfer->getUseBenefitVoucher(),
                ItemTransfer::USE_SHOPPING_POINTS => $itemTransfer->getUseShoppingPoints(),
            ];
        }

        return $cartIndex;
    }
}
