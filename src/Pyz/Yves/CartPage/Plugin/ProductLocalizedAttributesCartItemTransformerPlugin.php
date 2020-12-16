<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
