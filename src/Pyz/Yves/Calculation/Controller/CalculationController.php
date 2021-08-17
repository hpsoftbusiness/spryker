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
        $isBenefitVoucherUsed = (bool)($requestData['use_benefit_voucher'] ?? false);
        $quote->setUseBenefitVoucher($isBenefitVoucherUsed);
        $quote->setTotalUsedBenefitVouchersAmount($totalVoucherAmount);

        foreach ($quote->getItems() as $itemIndex => $item) {
            $useShoppingPoint = $requestData['items'][$itemIndex]['use_shopping_point'] ?? false;
            $item->setUseShoppingPoints($useShoppingPoint);
        }

        $quoteRecalculated = $this
            ->getClient()
            ->recalculate($quote);

        $this->getFactory()->getQuoteClient()->setQuote($quoteRecalculated);

        $responseTransfer = $this
            ->getFactory()
            ->createCalculationResponseMapper()
            ->toQuoteCalculationResponse(
                $quoteRecalculated,
                new QuoteCalculationResponseTransfer()
            );

        /**
         * Specification:
         * - Due to https://bugs.php.net/bug.php?id=72567 bug json_encode float serialization precision is set to 14.
         * Converting float to string prevents incorrect rounding of float values.
         *
         * @TODO Remove this typecasting once SP are refactored to be calculated in minor units.
         */
        $responseData = $responseTransfer->toArray();
        $responseData['total_used_shopping_points_amount'] = (string)$responseTransfer->getTotalUsedShoppingPointsAmount();

        return $this->jsonResponse(
            $responseData
        );
    }
}
