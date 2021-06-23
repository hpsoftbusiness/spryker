<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface;

class InternalDiscount implements InternalDiscountInterface
{
    /**
     * @var \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface[]
     */
    private $internalDiscountPlugins;

    /**
     * @param \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface[] $internalDiscountPlugins
     */
    public function __construct(array $internalDiscountPlugins)
    {
        $this->internalDiscountPlugins = $internalDiscountPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer[]
     */
    public function getInternalDiscounts(QuoteTransfer $quoteTransfer): array
    {
        $discountTransfers = [];
        foreach ($this->internalDiscountPlugins as $internalDiscountPlugin) {
            $discountTransfers[] = $this->createDiscountTransfer($internalDiscountPlugin, $quoteTransfer);
        }

        return $discountTransfers;
    }

    /**
     * @param \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface $internalDiscountPlugin
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    private function createDiscountTransfer(
        InternalDiscountPluginInterface $internalDiscountPlugin,
        QuoteTransfer $quoteTransfer
    ): DiscountTransfer {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName($internalDiscountPlugin->getDisplayName());
        $discountTransfer->setCalculatorPlugin($internalDiscountPlugin->getCalculatorPluginName());
        $discountTransfer->setCollectorPlugin($internalDiscountPlugin->getCollectorPluginName());
        $discountTransfer->setDiscountType(DiscountConstants::TYPE_INTERNAL_DISCOUNT);
        $discountTransfer->setCurrency($quoteTransfer->getCurrency());
        $discountTransfer->setPriceMode($quoteTransfer->getPriceMode());

        return $discountTransfer;
    }
}
