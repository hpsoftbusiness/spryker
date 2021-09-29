<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Discount;

use ArrayObject;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface;

class DiscountableItemWithDealsFilter implements DiscountableItemWithDealsFilterInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface
     */
    protected $itemTransferDealsChecker;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface $itemTransferDealsChecker
     */
    public function __construct(ItemTransferDealsCheckerInterface $itemTransferDealsChecker)
    {
        $this->itemTransferDealsChecker = $itemTransferDealsChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filter(CollectedDiscountTransfer $collectedDiscountTransfer): CollectedDiscountTransfer
    {
        $discountableItems = new ArrayObject();

        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItem) {
            $isShoppingPointsProduct = $collectedDiscountTransfer->getDiscount()->getDiscountType() !== DiscountConstants::TYPE_INTERNAL_DISCOUNT
                && $this->itemTransferDealsChecker->hasShoppingPointsDeals($discountableItem->getOriginalItem());
            $isBenefitVoucherProduct = $this->itemTransferDealsChecker->hasBenefitVoucherDeals($discountableItem->getOriginalItem());

            if (!$isShoppingPointsProduct && !$isBenefitVoucherProduct) {
                $discountableItems[] = $discountableItem;
            }
        }

        $collectedDiscountTransfer->setDiscountableItems($discountableItems);

        return $collectedDiscountTransfer;
    }
}
