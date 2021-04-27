<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget;

use Pyz\Yves\ContentProductWidget\Reader\ContentProductAbstractReader;
use Pyz\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunctionProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigFunctionProvider;
use SprykerShop\Yves\ContentProductWidget\ContentProductWidgetFactory as SprykerContentProductWidgetFactory;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface;
use Twig\Environment;

/**
 * @method \Pyz\Yves\ContentProductWidget\ContentProductWidgetConfig getConfig()
 */
class ContentProductWidgetFactory extends SprykerContentProductWidgetFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createContentProductAbstractListTwigFunctionProvider(Environment $twig, string $localeName): TwigFunctionProvider
    {
        return new ContentProductAbstractListTwigFunctionProvider(
            $twig,
            $localeName,
            $this->createContentProductAbstractReader()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface
     */
    public function createContentProductAbstractReader(): ContentProductAbstractReaderInterface
    {
        return new ContentProductAbstractReader(
            $this->getContentProductClient(),
            $this->getProductStorageClient(),
            $this->getStore(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(ContentProductWidgetDependencyProvider::STORE);
    }
}
