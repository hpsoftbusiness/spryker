<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Calculation\Controller;

use Generated\Shared\Transfer\QuoteCalculationResponseTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Client\Calculation\CalculationClient getClient()
 * @method \Pyz\Yves\Calculation\CalculationFactory getFactory()
 */
class CalculationController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function recalculateAction(Request $request): JsonResponse
    {
        $requestData = $this
            ->getFactory()
            ->getUtilEncodingService()
            ->decodeJson(
                $request->getContent(),
                true
            );

        $quote = $this
            ->getFactory()
            ->getCartClient()
            ->getQuote();

        $totalVoucherAmount = (int)($requestData['total_used_benefit_voucher_amount'] ?? 0);
        $totalVoucherAmount *= 100;
        $isBenefitVoucherUsed = $totalVoucherAmount > 0;
        $quote->setUseBenefitVoucher($isBenefitVoucherUsed);
        $quote->setTotalUsedBenefitVouchersAmount($totalVoucherAmount);

        foreach ($quote->getItems() as $itemIndex => $item) {
            $useShoppingPoint = $requestData['items'][$itemIndex]['use_shopping_point'] ?? false;
            $item->setUseShoppingPoints($useShoppingPoint);
        }

        $quoteRecalculated = $this
            ->getClient()
            ->recalculate($quote);

        $responseTransfer = $this
            ->getFactory()
            ->createCalculationResponseMapper()
            ->toQuoteCalculationResponse(
                $quoteRecalculated,
                new QuoteCalculationResponseTransfer()
            );

        return $this->jsonResponse(
            $responseTransfer->toArray()
        );
    }
}
