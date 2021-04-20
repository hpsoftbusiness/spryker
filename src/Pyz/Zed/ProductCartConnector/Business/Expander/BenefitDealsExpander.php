<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business\Expander;

use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Zed\ProductCartConnector\ProductCartConnectorConfig;

class BenefitDealsExpander implements BenefitDealsExpanderInterface
{
    /**
     * @var \Pyz\Zed\ProductCartConnector\ProductCartConnectorConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\ProductCartConnector\ProductCartConnectorConfig $config
     */
    public function __construct(ProductCartConnectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandWithShoppingPoints($itemTransfer);
            $this->expandWithBenefitVouchers($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function expandWithBenefitVouchers(ItemTransfer $itemTransfer): void
    {
        if ($this->isBenefitDealDataProvidedAtAttribute($itemTransfer)) {
            $salesPrice = $this->getBenefitVoucherSalesPrice($itemTransfer);
            $store = $this->getBenefitVoucherStore($itemTransfer);
            $amount = $this->getBenefitVoucherAmount($itemTransfer);

            $benefitVoucher = new BenefitVoucherDealDataTransfer();
            $benefitVoucher->setSalesPrice($salesPrice);
            $benefitVoucher->setAmount($amount);
            $benefitVoucher->setIsStore($store);

            $itemTransfer->setBenefitVoucherDealData($benefitVoucher);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isBenefitDealDataProvidedAtAttribute(ItemTransfer $itemTransfer): bool
    {
        return $this->getBenefitVoucherSalesPrice($itemTransfer)
            && $this->getBenefitVoucherStore($itemTransfer)
            && $this->getBenefitVoucherAmount($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function expandWithShoppingPoints(ItemTransfer $itemTransfer): void
    {
        $shoppingPointsDealTransfer = new ShoppingPointsDealTransfer();
        $isShoppingPointsStoreActive = $this->isShoppingPointStoreActiveForItem($itemTransfer);
        $shoppingPointsDealTransfer->setIsActive($isShoppingPointsStoreActive);
        $shoppingPointsDealTransfer->setShoppingPointsQuantity($this->getShoppingPointsQuantity($itemTransfer));

        $itemTransfer->setShoppingPointsDeal($shoppingPointsDealTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isShoppingPointStoreActiveForItem(ItemTransfer $itemTransfer): bool
    {
        return (bool)($itemTransfer->getConcreteAttributes()[$this->config->getShoppingPointStoreAttributeName()] ?? false);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float|null
     */
    private function getShoppingPointsQuantity(ItemTransfer $itemTransfer): ?float
    {
        $shoppingPointsQuantity = $itemTransfer->getConcreteAttributes()[$this->config->getShoppingPointsAmountAttributeName()] ?? null;
        if ($shoppingPointsQuantity !== null) {
            $shoppingPointsQuantity = (float)$shoppingPointsQuantity;
        }

        return $shoppingPointsQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float|null
     */
    private function getBenefitVoucherSalesPrice(ItemTransfer $itemTransfer): ?float
    {
        $benefitVoucherSalesPrice = $itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherSalesPriceAttributeName()] ?? null;

        if ($benefitVoucherSalesPrice !== null) {
            $benefitVoucherSalesPrice = (float)$benefitVoucherSalesPrice;
        }

        return $benefitVoucherSalesPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function getBenefitVoucherStore(ItemTransfer $itemTransfer): bool
    {
        return (bool)$itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherStoreAttributeName()];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float|null
     */
    private function getBenefitVoucherAmount(ItemTransfer $itemTransfer): ?float
    {
        $benefitVoucherAmount = $itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherAmountAttributeName()] ?? null;

        if ($benefitVoucherAmount !== null) {
            $benefitVoucherAmount = (float)$benefitVoucherAmount;
        }

        return $benefitVoucherAmount;
    }
}
