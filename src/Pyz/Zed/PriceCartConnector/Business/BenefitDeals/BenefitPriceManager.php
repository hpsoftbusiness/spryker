<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector\Business\BenefitDeals;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class BenefitPriceManager implements BenefitPriceManagerInterface
{
    /**
     * @var \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    private $priceProductFacade;

    /**
     * @param \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(PriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitPrices(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->expandItemWithBenefitPrices($itemTransfer, $cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    private function expandItemWithBenefitPrices(ItemTransfer $itemTransfer, CartChangeTransfer $cartChangeTransfer): void
    {
        if (!$this->isBenefitDealDataExist($itemTransfer)) {
            return;
        }

        $priceProductFilter = $this->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);
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
     * @param float $price
     *
     * @return void
     */
    private function setBenefitVoucherPrice(ItemTransfer $itemTransfer, float $price): void
    {
        $itemTransfer->getBenefitVoucherDealData()->setSalesPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $price
     *
     * @return void
     */
    private function setShoppingPointsPrice(ItemTransfer $itemTransfer, float $price): void
    {
        $itemTransfer->getShoppingPointsDeal()->setPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    private function createPriceProductFilterTransfer(
        CartChangeTransfer $cartChangeTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        return (new PriceProductFilterTransfer())
            ->fromArray($itemTransfer->toArray(), true)
            ->setStoreName($cartChangeTransfer->getQuote()->getStore()->getName())
            ->setPriceMode($cartChangeTransfer->getQuote()->getPriceMode())
            ->setCurrencyIsoCode($cartChangeTransfer->getQuote()->getCurrency()->getCode())
            ->setPriceTypeName($this->priceProductFacade->getBenefitPriceTypeName());
    }
}
