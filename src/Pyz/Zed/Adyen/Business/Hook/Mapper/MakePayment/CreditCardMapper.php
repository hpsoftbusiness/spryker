<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment;

use ArrayObject;
use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiMakePaymentRequestTransfer;
use Generated\Shared\Transfer\AdyenApiSplitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use SprykerEco\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper as SprykerEcoCreditCardMapper;

class CreditCardMapper extends SprykerEcoCreditCardMapper
{
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
            ->setSplits($this->createAdyenApiSplits($quoteTransfer, $adyenApiAmountTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\AdyenApiAmountTransfer $adyenApiAmountTransfer
     *
     * @return \ArrayObject
     */
    protected function createAdyenApiSplits(
        QuoteTransfer $quoteTransfer,
        AdyenApiAmountTransfer $adyenApiAmountTransfer
    ): ArrayObject {
        $commissionAmount = (int)round(
            $adyenApiAmountTransfer->getValue() * $this->config->getSplitAccountCommissionInterest()
        );
        $marketplaceAmount = (int)$adyenApiAmountTransfer->getValue() - $commissionAmount;

        $marketplaceSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($marketplaceAmount)
            )
            ->setType(AdyenConfig::SPLIT_TYPE_MARKETPLACE)
            ->setAccount($this->config->getSplitAccount())
            ->setReference($quoteTransfer->getPayment()->getAdyenPayment()->getSplitMarketplaceReference());

        $commissionSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($commissionAmount)
            )
            ->setType(AdyenConfig::SPLIT_TYPE_COMMISSION)
            ->setReference($quoteTransfer->getPayment()->getAdyenPayment()->getSplitCommissionReference());

        return new ArrayObject(
            [
                $marketplaceSplitTransfer,
                $commissionSplitTransfer,
            ]
        );
    }
}
