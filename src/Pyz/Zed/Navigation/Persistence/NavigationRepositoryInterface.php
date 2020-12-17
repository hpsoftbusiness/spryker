<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Persistence;

use Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface as SprykerNavigationRepositoryInterface;

interface NavigationRepositoryInterface extends SprykerNavigationRepositoryInterface
{
    /**
     * @param string[] $navigationKeys
     *
     * @return int[]
     */
    public function getNavigationIdsByKeys(array $navigationKeys): array;
}
