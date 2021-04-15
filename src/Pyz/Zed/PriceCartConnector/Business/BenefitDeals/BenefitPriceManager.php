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
            $this->expandItemWithShoppingPointsPrice($itemTransfer, $cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    private function expandItemWithShoppingPointsPrice(ItemTransfer $itemTransfer, CartChangeTransfer $cartChangeTransfer): void
    {
        $shoppingPointsDealTransfer = $itemTransfer->getShoppingPointsDeal();
        if (!$shoppingPointsDealTransfer || !$shoppingPointsDealTransfer->getIsActive()) {
            return;
        }

        $priceProductFilter = $this->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);
        $shoppingPointsDealTransfer->setPrice($this->priceProductFacade->findPriceFor($priceProductFilter));
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
            ->setPriceTypeName($this->priceProductFacade->getSPBenefitPriceTypeName());
    }
}
