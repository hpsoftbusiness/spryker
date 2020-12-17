<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Business;

use Pyz\Zed\Navigation\Business\Tree\NavigationTreeReader;
use Spryker\Zed\Navigation\Business\NavigationBusinessFactory as SprykerNavigationBusinessFactory;

class NavigationBusinessFactory extends SprykerNavigationBusinessFactory
{
    /**
     * @return \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    public function createNavigationTreeReader()
    {
        return new NavigationTreeReader($this->getQueryContainer());
    }
}
