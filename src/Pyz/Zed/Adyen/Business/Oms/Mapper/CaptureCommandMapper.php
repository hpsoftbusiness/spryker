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
use SprykerEco\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper as SprykerEcoCaptureCommandMapper;

class CaptureCommandMapper extends SprykerEcoCaptureCommandMapper
{
    protected const SPLIT_TYPE_MARKETPLACE = 'MarketPlace';
    protected const SPLIT_TYPE_COMMISSION = 'Commission';

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
                ->setSplits($this->createAdyenApiSplits($adyenApiAmountTransfer))
        );

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\AdyenApiAmountTransfer $adyenApiAmountTransfer
     *
     * @return \ArrayObject
     */
    protected function createAdyenApiSplits(AdyenApiAmountTransfer $adyenApiAmountTransfer): ArrayObject
    {
        $commissionAmount = (int)round($adyenApiAmountTransfer->getValue() * $this->config->getSplitAccountCommissionInterest());
        $marketplaceAmount = (int)$adyenApiAmountTransfer->getValue() - $commissionAmount;

        $marketplaceSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($marketplaceAmount)
            )
            ->setType(static::SPLIT_TYPE_MARKETPLACE)
            ->setAccount($this->config->getSplitAccount())
            ->setReference($this->generateReference(static::SPLIT_TYPE_MARKETPLACE));

        $commissionSplitTransfer = (new AdyenApiSplitTransfer())
            ->setAmount(
                (new AdyenApiAmountTransfer())->setValue($commissionAmount)
            )
            ->setType(static::SPLIT_TYPE_COMMISSION)
            ->setReference($this->generateReference(static::SPLIT_TYPE_COMMISSION));

        return new ArrayObject([
            $marketplaceSplitTransfer,
            $commissionSplitTransfer,
        ]);
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
