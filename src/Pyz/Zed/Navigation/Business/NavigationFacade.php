<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Business;

use Spryker\Zed\Navigation\Business\NavigationFacade as SprykerNavigationFacade;

/**
 * @method \Pyz\Zed\Navigation\Persistence\NavigationRepositoryInterface getRepository()
 */
class NavigationFacade extends SprykerNavigationFacade implements NavigationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $navigationKeys
     *
     * @return int[]
     */
    public function getNavigationIdsByKeys(array $navigationKeys): array
    {
        return $this->getRepository()->getNavigationIdsByKeys($navigationKeys);
    }
}
