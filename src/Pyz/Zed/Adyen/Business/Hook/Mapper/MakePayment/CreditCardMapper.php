<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment;

use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiMakePaymentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\Adyen\Business\Traits\AdyenApiSplitsTrait;
use Pyz\Zed\Adyen\Business\Traits\AdyenPaymentTrait;
use SprykerEco\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper as SprykerEcoCreditCardMapper;

class CreditCardMapper extends SprykerEcoCreditCardMapper
{
    use AdyenApiSplitsTrait;
    use AdyenPaymentTrait;

    /**
     * @var \Pyz\Zed\Adyen\AdyenConfig
     */
    protected $config;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AdyenApiMakePaymentRequestTransfer
     */
    protected function createMakePaymentRequestTransfer(
        QuoteTransfer $quoteTransfer
    ): AdyenApiMakePaymentRequestTransfer {
        $adyenApiAmountTransfer = $this->createAmountTransfer($quoteTransfer);

        return (new AdyenApiMakePaymentRequestTransfer())
            ->setMerchantAccount($this->config->getMerchantAccount())
            ->setReference($quoteTransfer->getPayment()->getAdyenPayment()->getReference())
            ->setAmount($adyenApiAmountTransfer)
            ->setReturnUrl($this->getReturnUrl())
            ->setCountryCode($quoteTransfer->getBillingAddress()->getIso2Code())
            ->setShopperIP($quoteTransfer->getPayment()->getAdyenPayment()->getClientIp())
            ->setSplits(
                $this->createAdyenApiSplits(
                    $quoteTransfer->getPayment()->getAdyenPayment()->getSplitMarketplaceReference(),
                    $quoteTransfer->getPayment()->getAdyenPayment()->getSplitCommissionReference(),
                    $adyenApiAmountTransfer
                )
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AdyenApiAmountTransfer
     */
    protected function createAmountTransfer(QuoteTransfer $quoteTransfer): AdyenApiAmountTransfer
    {
        $paymentsCollection = clone $quoteTransfer->getPayments();
        $paymentsCollection->append($quoteTransfer->getPayment());
        $amount = $this->getAdyenPaymentTransfer($paymentsCollection)->getAmount();

        return (new AdyenApiAmountTransfer())
            ->setCurrency($quoteTransfer->getCurrency()->getCode())
            ->setValue($amount);
    }
}
