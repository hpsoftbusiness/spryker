<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Oms\Mapper;

use Generated\Shared\Transfer\AdyenApiCaptureRequestTransfer;
use Generated\Shared\Transfer\AdyenApiRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Zed\Adyen\Business\Traits\AdyenApiSplitsTrait;
use Pyz\Zed\Adyen\Business\Traits\AdyenPaymentTrait;
use SprykerEco\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper as SprykerEcoCaptureCommandMapper;

class CaptureCommandMapper extends SprykerEcoCaptureCommandMapper
{
    use AdyenPaymentTrait;
    use AdyenApiSplitsTrait;

    /**
     * @var \Pyz\Zed\Adyen\AdyenConfig
     */
    protected $config;

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
                ->setSplits(
                    $this->createAdyenApiSplits(
                        $paymentAdyen->getSplitMarketplaceReference(),
                        $paymentAdyen->getSplitCommissionReference(),
                        $adyenApiAmountTransfer
                    )
                )
        );

        return $request;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmountToModify(array $orderItems, OrderTransfer $orderTransfer): int
    {
        if ($orderTransfer->getItems()->count() === count($orderItems)) {
            return $this->getAdyenPaymentTransfer($orderTransfer->getPayments())->getAmount();
        }

        return parent::getAmountToModify($orderItems, $orderTransfer);
    }
}
