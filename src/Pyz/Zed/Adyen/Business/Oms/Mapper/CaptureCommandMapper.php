<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Oms\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\AdyenApiAmountTransfer;
use Generated\Shared\Transfer\AdyenApiCaptureRequestTransfer;
use Generated\Shared\Transfer\AdyenApiRequestTransfer;
use Generated\Shared\Transfer\AdyenApiSplitTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentAdyenTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use SprykerEco\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper as SprykerEcoCaptureCommandMapper;

class CaptureCommandMapper extends SprykerEcoCaptureCommandMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AdyenApiRequestTransfer
     */
    public function buildRequestTransfer(array $orderItems, OrderTransfer $orderTransfer): AdyenApiRequestTransfer
    {
        $request = new AdyenApiRequestTransfer();
        $paymentAdyen = $this->reader->getPaymentAdyenByOrderTransfer($orderTransfer);
        $adyenApiAmountTransfer = $this->createAmountTransfer($orderItems, $orderTransfer);
        $request->setCaptureRequest(
            (new AdyenApiCaptureRequestTransfer())
                ->setMerchantAccount($this->config->getMerchantAccount())
                ->setOriginalReference($paymentAdyen->getPspReference())
                ->setOriginalMerchantReference($paymentAdyen->getReference())
                ->setModificationAmount($adyenApiAmountTransfer)
                ->setSplits($this->createAdyenApiSplits($paymentAdyen, $adyenApiAmountTransfer))
        );

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAdyenTransfer $paymentAdyenTransfer
     * @param \Generated\Shared\Transfer\AdyenApiAmountTransfer $adyenApiAmountTransfer
     *
     * @return \ArrayObject
     */
    protected function createAdyenApiSplits(
        PaymentAdyenTransfer $paymentAdyenTransfer,
        AdyenApiAmountTransfer $adyenApiAmountTransfer
    ): ArrayObject {
        $commissionAmount = (int)round($adyenApiAmountTransfer->getValue() * $this->config->getSplitAccountCommissionInterest());
        $marketplaceAmount = (int)$adyenApiAmountTransfer->getValue() - $commissionAmount;

        $marketplaceSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($marketplaceAmount)
            )
            ->setType(AdyenConfig::SPLIT_TYPE_MARKETPLACE)
            ->setAccount($this->config->getSplitAccount())
            ->setReference($paymentAdyenTransfer->getSplitMarketplaceReference());

        $commissionSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($commissionAmount)
            )
            ->setType(AdyenConfig::SPLIT_TYPE_COMMISSION)
            ->setReference($paymentAdyenTransfer->getSplitCommissionReference());

        return new ArrayObject([
            $marketplaceSplitTransfer,
            $commissionSplitTransfer,
        ]);
    }
}
