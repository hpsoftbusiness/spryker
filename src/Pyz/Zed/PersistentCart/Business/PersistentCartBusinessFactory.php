<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PersistentCart\Business;

use Pyz\Zed\PersistentCart\Business\Model\CartOperation;
use Pyz\Zed\PersistentCart\PersistentCartDependencyProvider;
use Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface;
use Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory as SprykerPersistentCartBusinessFactory;

class PersistentCartBusinessFactory extends SprykerPersistentCartBusinessFactory
{
    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface
     */
    public function createCartOperation(): CartOperationInterface
    {
        return new CartOperation(
            $this->getQuoteItemFinderPlugin(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation(),
            $this->getQuoteFacade(),
            $this->getPersistentQuoteEqualizerPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\PersistentCart\Dependency\Plugin\PersistentQuoteEqualizerPluginInterface[]
     */
    public function getPersistentQuoteEqualizerPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_PERSISTENT_QUOTE_EQUALIZER);
    }
}
