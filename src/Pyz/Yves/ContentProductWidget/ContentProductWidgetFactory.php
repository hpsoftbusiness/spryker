<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget;

use Pyz\Yves\ContentProductWidget\Reader\ContentProductAbstractReader;
use Pyz\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentProductWidget\ContentProductWidgetFactory as SprykerContentProductWidgetFactory;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface;
use SprykerShop\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction as SprykerContentProductAbstractListTwigFunction;
use Twig\Environment;

class ContentProductWidgetFactory extends SprykerContentProductWidgetFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Pyz\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction
     */
    public function createContentProductAbstractListTwigFunction(Environment $twig, string $localeName): SprykerContentProductAbstractListTwigFunction
    {
        return new ContentProductAbstractListTwigFunction(
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
            $this->getStore()
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
