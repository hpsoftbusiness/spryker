<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentNavigationWidget;

use Pyz\Client\Catalog\CatalogClientInterface;
use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Yves\ContentNavigationWidget\Reader\CategoryReader;
use Pyz\Yves\ContentNavigationWidget\Reader\CategoryReaderInterface;
use Pyz\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetFactory as SprykerShopContentNavigationWidgetFactory;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction as SprykerShopContentNavigationTwigFunction;
use Twig\Environment;

/**
 * @method \Pyz\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig getConfig()
 */
class ContentNavigationWidgetFactory extends SprykerShopContentNavigationWidgetFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction
     */
    public function createContentNavigationTwigFunction(Environment $twig, string $localeName): SprykerShopContentNavigationTwigFunction
    {
        return new ContentNavigationTwigFunction(
            $twig,
            $localeName,
            $this->getContentNavigationClient(),
            $this->getNavigationStorageClient(),
            $this->getConfig(),
            $this->getCustomerClient(),
            $this->getSessionClient(),
            $this->getUtilEncodingService(),
            $this->createCategoryReader()
        );
    }

    /**
     * @return \Pyz\Yves\ContentNavigationWidget\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader($this->getCatalogClient());
    }

    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Pyz\Client\Catalog\CatalogClientInterface
     */
    public function getCatalogClient(): CatalogClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
