<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Product\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \Spryker\Client\Product\ProductFactory getFactory()
 */
class ProductLocalizedAttributesQuoteTransferExpanderPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer)
    {
        $itemTransfers = [];
        $currentLocaleName = $this->getFactory()->getLocaleClient()->getCurrentLocale();
        foreach ($quoteTransfer->getItems() as $item) {
            $itemTransfers[] = $item
                ->setConcreteAttributes(
                    array_merge(
                        $item->getConcreteAttributes(),
                        $item->getConcreteLocalizedAttributes()[$currentLocaleName] ?? []
                    )
                );
        }

        $quoteTransfer
            ->setItems(new ArrayObject($itemTransfers));

        return $quoteTransfer;
    }
}
