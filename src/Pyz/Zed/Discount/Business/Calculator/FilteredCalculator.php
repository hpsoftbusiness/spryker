<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Calculator\FilteredCalculator as SprykerFilteredCalculator;

class FilteredCalculator extends SprykerFilteredCalculator
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    protected function setSuccessfulDiscountAddMessage(DiscountTransfer $discountTransfer): void
    {
        if ($discountTransfer->getDiscountType() === DiscountConstants::TYPE_INTERNAL_DISCOUNT) {
            return;
        }

        parent::setSuccessfulDiscountAddMessage($discountTransfer);
    }
}
