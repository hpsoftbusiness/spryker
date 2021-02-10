<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use SprykerEco\Yves\Adyen\Handler\AdyenPaymentHandler as SprykerEcoAdyenPaymentHandler;
use Symfony\Component\HttpFoundation\Request;

class AdyenPaymentHandler extends SprykerEcoAdyenPaymentHandler
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = parent::addPaymentToQuote($request, $quoteTransfer);

        $quoteTransfer->getPayment()
            ->getAdyenPayment()
            ->setSplitMarketplaceReference($this->generateReference(AdyenConfig::SPLIT_TYPE_MARKETPLACE))
            ->setSplitCommissionReference($this->generateReference(AdyenConfig::SPLIT_TYPE_COMMISSION));

        return $quoteTransfer;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function generateReference(string $type): string
    {
        $params = [
            $type,
            time(),
            rand(100, 1000),
        ];

        return substr(hash('sha256', implode('-', $params)), 0, 32);
    }
}
