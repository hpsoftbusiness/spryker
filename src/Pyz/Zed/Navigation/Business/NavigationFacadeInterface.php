<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Business;

use Spryker\Zed\Navigation\Business\NavigationFacadeInterface as SprykerNavigationFacadeInterface;

interface NavigationFacadeInterface extends SprykerNavigationFacadeInterface
{
    /**
     * Specification:
     * - Gets navigation IDs by navigation keys passed.
     *
     * @api
     *
     * @param string[] $navigationKeys
     *
     * @return int[]
     */
    public function getNavigationIdsByKeys(array $navigationKeys): array;
}
