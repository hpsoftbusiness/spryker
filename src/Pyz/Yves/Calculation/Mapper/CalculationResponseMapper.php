<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Calculation\Mapper;

use Generated\Shared\Transfer\QuoteCalculationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsFormattedTransfer;
use Spryker\Client\Money\MoneyClientInterface;

class CalculationResponseMapper
{
    /**
     * @var \Spryker\Client\Money\MoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Spryker\Client\Money\MoneyClientInterface $moneyClient
     */
    public function __construct(
        MoneyClientInterface $moneyClient
    ) {
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteCalculationResponseTransfer $quoteCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCalculationResponseTransfer
     */
    public function toQuoteCalculationResponse(
        QuoteTransfer $quoteTransfer,
        QuoteCalculationResponseTransfer $quoteCalculationResponseTransfer
    ): QuoteCalculationResponseTransfer {
        $quoteCalculationResponseTransfer->fromArray(
            $quoteTransfer->toArray(),
            true
        );

        $totalsFormatted = (new TotalsFormattedTransfer())
            ->setGrandTotal(
                $this->getFormattedAmount(
                    $quoteTransfer->getTotals()->getGrandTotal()
                )
            )
            ->setPriceToPay(
                $this->getFormattedAmount(
                    $quoteTransfer->getTotals()->getPriceToPay()
                )
            );

        $quoteCalculationResponseTransfer->setTotalsFormatted($totalsFormatted);

        return $quoteCalculationResponseTransfer;
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    protected function getFormattedAmount(int $amount): string
    {
        return $this->moneyClient->formatWithSymbol(
            $this->moneyClient->fromInteger($amount, null)
        );
    }
}
