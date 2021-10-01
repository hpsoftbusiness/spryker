<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Utils;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemTransferDealsCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function hasBenefitVoucherDeals(ItemTransfer $itemTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function hasShoppingPointsDeals(ItemTransfer $itemTransfer): bool;
}
