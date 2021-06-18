<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Oms\Mapper;

use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiRefundRequestTransfer;
use Generated\Shared\Transfer\AdyenApiRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Adyen\AdyenConfig;
use Pyz\Zed\Adyen\Business\Traits\AdyenApiSplitsTrait;
use SprykerEco\Zed\Adyen\Business\Reader\AdyenReaderInterface;

class RefundCommandMapper implements RefundCommandMapperInterface
{
    use AdyenApiSplitsTrait;

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
            ->setSplits(
                $this->createAdyenApiSplits(
                    $paymentAdyen->getSplitMarketplaceReference(),
                    $paymentAdyen->getSplitCommissionReference(),
                    $adyenApiAmountTransfer
                )
            );

        return (new AdyenApiRequestTransfer())
            ->setRefundRequest($adyenApiRefundRequestTransfer);
    }
}
