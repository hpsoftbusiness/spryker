<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PersistentCart\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface PersistentQuoteEqualizerPluginInterface
{
    /**
     * Specification:
     * - Persistent quote (from the database) is hydrated with session-quote specific parameters.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sessionQuoteTransfer
     *
     * @return void
     */
    public function equalize(
        QuoteTransfer $persistentQuoteTransfer,
        QuoteTransfer $sessionQuoteTransfer
    ): void;
}
