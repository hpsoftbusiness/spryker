<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Cart\Item;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;

class ItemBenefitPriceExpander implements ItemBenefitPriceExpanderInterface
{
    /**
     * @var \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductFacadeInterface $priceProductFacade,
        StoreClientInterface $storeClient
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $item) {
            $this->expandItem($item);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItem(ItemTransfer $itemTransfer)
    {
        $benefitPrice = $this
            ->priceProductFacade
            ->findPriceBySku(
                $itemTransfer->getSku(),
                $this->priceProductFacade->getBenefitPriceTypeName()
            );

        $itemTransfer->setBenefitUnitGrossPrice($benefitPrice);
    }
}
