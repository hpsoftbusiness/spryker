<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Url\Business;

use Pyz\Zed\Url\Business\Url\UrlCreator;
use Pyz\Zed\Url\Business\Url\UrlUpdater;
use Spryker\Zed\Url\Business\UrlBusinessFactory as SprykerUrlBusinessFactory;

/**
 * @method \Pyz\Zed\Url\Persistence\UrlQueryContainerInterface getQueryContainer()
 */
class UrlBusinessFactory extends SprykerUrlBusinessFactory
{
    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorInterface
     */
    public function createUrlCreator()
    {
        $urlCreator = new UrlCreator($this->getQueryContainer(), $this->createUrlReader(), $this->createUrlActivator());

        $this->attachUrlCreatorObservers($urlCreator);

        return $urlCreator;
    }

    /**
     * @return \Pyz\Zed\Url\Business\Url\UrlUpdater
     */
    public function createUrlUpdater(): UrlUpdater
    {
        $urlUpdater = new UrlUpdater($this->getQueryContainer(), $this->createUrlReader(), $this->createUrlActivator());

        $this->attachUrlUpdaterObservers($urlUpdater);

        return $urlUpdater;
    }
}
