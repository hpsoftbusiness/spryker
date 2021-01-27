<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Oms\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiRefundRequestTransfer;
use Generated\Shared\Transfer\AdyenApiRequestTransfer;
use Generated\Shared\Transfer\AdyenApiSplitTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentAdyenTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use Pyz\Zed\Adyen\AdyenConfig;
use SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface;

class RefundCommandMapper implements RefundCommandMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface
     */
    protected $reader;

    /**
     * @var \Pyz\Zed\Adyen\AdyenConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface $reader
     * @param \Pyz\Zed\Adyen\AdyenConfig $config
     */
    public function __construct(
        AdyenReaderInterface $reader,
        AdyenConfig $config
    ) {
        $this->reader = $reader;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\AdyenApiRequestTransfer
     */
    public function buildRequestTransfer(
        OrderTransfer $orderTransfer,
        RefundTransfer $refundTransfer
    ): AdyenApiRequestTransfer {
        $paymentAdyen = $this->reader->getPaymentAdyenByOrderTransfer($orderTransfer);
        $adyenApiAmountTransfer = (new AdyenApiAmountTransfer())
            ->setValue($refundTransfer->getAmount())
            ->setCurrency($orderTransfer->getCurrencyIsoCode());

        $adyenApiRefundRequestTransfer = (new AdyenApiRefundRequestTransfer())
            ->setMerchantAccount($this->config->getMerchantAccount())
            ->setOriginalReference($paymentAdyen->getPspReference())
            ->setOriginalMerchantReference($paymentAdyen->getReference())
            ->setModificationAmount($adyenApiAmountTransfer)
            ->setSplits($this->createAdyenApiSplits($paymentAdyen, $adyenApiAmountTransfer));

        return (new AdyenApiRequestTransfer())
            ->setRefundRequest($adyenApiRefundRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAdyenTransfer $paymentAdyen
     * @param \Generated\Shared\Transfer\AdyenApiAmountTransfer $adyenApiAmountTransfer
     *
     * @return \ArrayObject
     */
    protected function createAdyenApiSplits(
        PaymentAdyenTransfer $paymentAdyen,
        AdyenApiAmountTransfer $adyenApiAmountTransfer
    ): ArrayObject {
        $commissionAmount = (int)round($adyenApiAmountTransfer->getValue() * $this->config->getSplitAccountCommissionInterest());
        $marketplaceAmount = (int)$adyenApiAmountTransfer->getValue() - $commissionAmount;

        $marketplaceSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($marketplaceAmount)
            )
            ->setType(SharedAdyenConfig::SPLIT_TYPE_MARKETPLACE)
            ->setAccount($this->config->getSplitAccount())
            ->setReference($paymentAdyen->getSplitMarketplaceReference());

        $commissionSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($commissionAmount)
            )
            ->setType(SharedAdyenConfig::SPLIT_TYPE_COMMISSION)
            ->setReference($paymentAdyen->getSplitCommissionReference());

        return new ArrayObject([
            $marketplaceSplitTransfer,
            $commissionSplitTransfer,
        ]);
    }
}
