<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Pyz\Zed\Discount\DiscountConfig getConfig()
 * @method \Pyz\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class ShoppingPointsItemDiscountCollectorPlugin extends AbstractPlugin implements InternalDiscountCollectorPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFacade()->collectByUseShoppingPoints($quoteTransfer, new ClauseTransfer());
    }
}
