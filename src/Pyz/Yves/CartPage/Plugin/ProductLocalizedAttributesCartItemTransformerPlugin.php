<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CartPage\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class ProductLocalizedAttributesCartItemTransformerPlugin extends AbstractPlugin implements CartItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformCartItems(array $cartItems, QuoteTransfer $quoteTransfer): array
    {
        foreach ($cartItems as $cartItem) {
            $cartItem->setConcreteAttributes(array_merge(
                $cartItem->getConcreteAttributes(),
                $cartItem->getConcreteLocalizedAttributes()[$this->getFactory()->getLocale()] ?? []
            ));
        }

        return $cartItems;
    }
}
