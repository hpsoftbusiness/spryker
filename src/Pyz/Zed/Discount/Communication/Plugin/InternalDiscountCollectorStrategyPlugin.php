<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Pyz\Zed\Discount\DiscountConfig getConfig()
 * @method \Pyz\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class InternalDiscountCollectorStrategyPlugin extends AbstractPlugin implements CollectorStrategyPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function accept(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $discountTransfer->getDiscountType() === DiscountConstants::TYPE_INTERNAL_DISCOUNT && $discountTransfer->getCollectorPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->collectForInternalDiscount($discountTransfer, $quoteTransfer);
    }
}
