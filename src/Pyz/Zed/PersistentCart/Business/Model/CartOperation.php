<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Business\Model\CartOperation as SprykerCartOperation;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class CartOperation extends SprykerCartOperation
{
    /**
     * @var \Pyz\Zed\PersistentCart\Dependency\Plugin\PersistentQuoteEqualizerPluginInterface[]
     */
    private $persistentQuoteEqualizerPlugins;

    /**
     * @param \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface $itemFinderPlugin
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface $quoteResolver
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface $quoteItemOperations
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Pyz\Zed\PersistentCart\Dependency\Plugin\PersistentQuoteEqualizerPluginInterface[] $persistentQuoteEqualizerPlugins
     */
    public function __construct(
        QuoteItemFinderPluginInterface $itemFinderPlugin,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        QuoteResolverInterface $quoteResolver,
        QuoteItemOperationInterface $quoteItemOperations,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        array $persistentQuoteEqualizerPlugins
    ) {
        parent::__construct(
            $itemFinderPlugin,
            $quoteResponseExpander,
            $quoteResolver,
            $quoteItemOperations,
            $quoteFacade
        );

        $this->persistentQuoteEqualizerPlugins = $persistentQuoteEqualizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate($quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();
        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $customerQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        if ($this->quoteFacade->isQuoteLocked($customerQuoteTransfer)) {
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }

        $this->equalizePersistentQuote($customerQuoteTransfer, $quoteTransfer);
        $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);

        return $this->quoteItemOperation->validate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sessionQuoteTransfer
     *
     * @return void
     */
    private function equalizePersistentQuote(
        QuoteTransfer $persistentQuoteTransfer,
        QuoteTransfer $sessionQuoteTransfer
    ): void {
        foreach ($this->persistentQuoteEqualizerPlugins as $persistentQuoteEqualizerPlugin) {
            $persistentQuoteEqualizerPlugin->equalize($persistentQuoteTransfer, $sessionQuoteTransfer);
        }
    }
}
