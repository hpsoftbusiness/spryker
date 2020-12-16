<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\Product\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractFactory;
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
            ->setItems(new \ArrayObject($itemTransfers));

        return $quoteTransfer;
    }
}
