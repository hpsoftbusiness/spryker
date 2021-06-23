<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model\Item\Expander;

use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Shared\PriceProduct\PriceProductConfig;
use Pyz\Zed\BenefitDeal\BenefitDealConfig;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ItemBenefitExpander implements ItemBenefitExpanderInterface
{
    /**
     * @var \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    private $priceProductFacade;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private $storeClient;

    /**
     * @var \Pyz\Zed\BenefitDeal\BenefitDealConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     * @param \Pyz\Zed\BenefitDeal\BenefitDealConfig $config
     */
    public function __construct(
        PriceProductFacadeInterface $priceProductFacade,
        StoreClientInterface $storeClient,
        BenefitDealConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
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
            $this->expandWithShoppingPoints($itemTransfer);
            $this->expandWithBenefitVouchers($itemTransfer);
            $this->expandItemWithBenefitPrices($itemTransfer, $currencyIsoCode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function expandWithBenefitVouchers(ItemTransfer $itemTransfer): void
    {
        if ($this->isBenefitDealDataProvidedAtAttribute($itemTransfer)) {
            $benefitVoucher = new BenefitVoucherDealDataTransfer();
            $benefitVoucher->setAmount($this->getBenefitVoucherAmount($itemTransfer));
            $benefitVoucher->setIsStore($this->getBenefitVoucherStore($itemTransfer));

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
        return $this->getBenefitVoucherStore($itemTransfer)
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
     * @return bool
     */
    private function getBenefitVoucherStore(ItemTransfer $itemTransfer): bool
    {
        return (bool)($itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherStoreAttributeName()] ?? false);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int|null
     */
    private function getBenefitVoucherAmount(ItemTransfer $itemTransfer): ?int
    {
        $benefitVoucherAmount = $itemTransfer->getConcreteAttributes()[$this->config->getBenefitVoucherAmountAttributeName()] ?? null;

        if ($benefitVoucherAmount !== null) {
            $benefitVoucherAmount = (int)$benefitVoucherAmount * 100;
        }

        return $benefitVoucherAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $currencyIsoCode
     *
     * @return void
     */
    private function expandItemWithBenefitPrices(
        ItemTransfer $itemTransfer,
        string $currencyIsoCode
    ): void {
        if (!$this->isBenefitDealDataExist($itemTransfer)) {
            return;
        }

        $priceProductFilter = $this->createPriceProductFilterTransfer($itemTransfer, $currencyIsoCode);
        $price = $this->priceProductFacade->findPriceFor($priceProductFilter);

        if ($this->assertBenefitVoucherDealData($itemTransfer)) {
            $this->setBenefitVoucherPrice($itemTransfer, $price);
        }

        if ($this->assertShoppingPointsDealData($itemTransfer)) {
            $this->setShoppingPointsPrice($itemTransfer, $price);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isBenefitDealDataExist(ItemTransfer $itemTransfer): bool
    {
        return $this->assertBenefitVoucherDealData($itemTransfer)
            || $this->assertShoppingPointsDealData($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherDealData(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsDealData(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireShoppingPointsDeal();

            return $itemTransfer->getShoppingPointsDeal()->getIsActive();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $price
     *
     * @return void
     */
    private function setBenefitVoucherPrice(ItemTransfer $itemTransfer, int $price): void
    {
        $itemTransfer->getBenefitVoucherDealData()->setSalesPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $price
     *
     * @return void
     */
    private function setShoppingPointsPrice(ItemTransfer $itemTransfer, int $price): void
    {
        $itemTransfer->getShoppingPointsDeal()->setPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    private function createPriceProductFilterTransfer(
        ItemTransfer $itemTransfer,
        string $currencyIsoCode
    ): PriceProductFilterTransfer {
        $storeTransfer = $this->storeClient->getCurrentStore();

        return (new PriceProductFilterTransfer())
            ->fromArray($itemTransfer->toArray(), true)
            ->setStoreName($storeTransfer->getName())
            ->setPriceMode(PriceProductConfig::PRICE_GROSS_MODE)
            ->setCurrencyIsoCode($currencyIsoCode)
            ->setPriceTypeName($this->priceProductFacade->getBenefitPriceTypeName());
    }
}
