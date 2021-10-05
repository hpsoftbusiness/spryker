<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model\Item\Expander;

use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Zed\BenefitDeal\BenefitDealConfig;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ItemBenefitExpander implements ItemBenefitExpanderInterface
{
    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private $storeClient;

    /**
     * @var \Pyz\Zed\BenefitDeal\BenefitDealConfig
     */
    private $config;

    /**
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     * @param \Pyz\Zed\BenefitDeal\BenefitDealConfig $config
     */
    public function __construct(
        StoreClientInterface $storeClient,
        BenefitDealConfig $config
    ) {
        $this->storeClient = $storeClient;
        $this->config = $config;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string|null $currencyIsoCode
     *
     * @return void
     */
    public function expandItems(iterable $itemTransfers, ?string $currencyIsoCode): void
    {
        if ($currencyIsoCode === null) {
             $currencyIsoCode = $this->storeClient->getCurrentStore()->getSelectedCurrencyIsoCode();
        }

        foreach ($itemTransfers as $itemTransfer) {
            if ($this->isBenefitVoucherStoreActive($itemTransfer)) {
                $this->expandWithBenefitVouchers($itemTransfer, $currencyIsoCode);
            } elseif ($this->isShoppingPointStoreActive($itemTransfer)) {
                $this->expandWithShoppingPoints($itemTransfer, $currencyIsoCode);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $currencyIsoCode
     *
     * @return void
     */
    private function expandWithBenefitVouchers(ItemTransfer $itemTransfer, string $currencyIsoCode): void
    {
        $benefitVoucher = new BenefitVoucherDealDataTransfer();
        $benefitVoucher->setAmount($this->getBenefitVoucherAmount($itemTransfer));
        $benefitVoucher->setIsStore($this->isBenefitVoucherStoreActive($itemTransfer));
        $benefitVoucher->setSalesPrice($this->getBenefitPriceAmount($itemTransfer));

        if ($this->assertBenefitVoucherDealData($benefitVoucher)) {
            $itemTransfer->setBenefitVoucherDealData($benefitVoucher);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $currencyIsoCode
     *
     * @return void
     */
    private function expandWithShoppingPoints(ItemTransfer $itemTransfer, string $currencyIsoCode): void
    {
        $shoppingPointsDealTransfer = new ShoppingPointsDealTransfer();
        $shoppingPointsDealTransfer->setIsActive($this->isShoppingPointStoreActive($itemTransfer));
        $shoppingPointsDealTransfer->setPrice($this->getBenefitPriceAmount($itemTransfer));
        $shoppingPointsDealTransfer->setShoppingPointsQuantity($this->getShoppingPointsQuantity($itemTransfer));

        if ($this->assertShoppingPointsDealData($shoppingPointsDealTransfer)) {
            $itemTransfer->setShoppingPointsDeal($shoppingPointsDealTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isShoppingPointStoreActive(ItemTransfer $itemTransfer): bool
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
     * @return bool
     */
    private function isBenefitVoucherStoreActive(ItemTransfer $itemTransfer): bool
    {
        return (bool)($itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherStoreAttributeName()] ?? false);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    private function getBenefitVoucherAmount(ItemTransfer $itemTransfer): int
    {
        $defaultPrice = $itemTransfer->getOriginUnitGrossPrice();
        $benefitPrice = $itemTransfer->getBenefitUnitGrossPrice();

        return $defaultPrice - $benefitPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int|null
     */
    private function getBenefitPriceAmount(ItemTransfer $itemTransfer): ?int
    {
        return $itemTransfer->getBenefitUnitGrossPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\BenefitVoucherDealDataTransfer $benefitVoucherDealDataTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherDealData(BenefitVoucherDealDataTransfer $benefitVoucherDealDataTransfer): bool
    {
        try {
            $benefitVoucherDealDataTransfer->requireIsStore();
            $benefitVoucherDealDataTransfer->requireAmount();
            $benefitVoucherDealDataTransfer->requireSalesPrice();

            return true;
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingPointsDealTransfer $shoppingPointsDealTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsDealData(ShoppingPointsDealTransfer $shoppingPointsDealTransfer): bool
    {
        try {
            $shoppingPointsDealTransfer->requireIsActive();
            $shoppingPointsDealTransfer->requirePrice();
            $shoppingPointsDealTransfer->requireShoppingPointsQuantity();

            return true;
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
