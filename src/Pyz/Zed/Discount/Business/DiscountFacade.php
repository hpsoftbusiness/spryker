<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade as SprykerDiscountFacade;

/**
 * @method \Pyz\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class DiscountFacade extends SprykerDiscountFacade implements DiscountFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectByUseShoppingPoints(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        return $this->getFactory()->createUseShoppingPointsCollector()->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectForInternalDiscount(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()->createInternalDiscountCollector()->collect($discountTransfer, $quoteTransfer);
    }
}
