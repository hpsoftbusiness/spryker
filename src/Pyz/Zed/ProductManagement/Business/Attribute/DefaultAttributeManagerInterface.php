<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business\Attribute;

interface DefaultAttributeManagerInterface
{
    /**
     * @return null[]
     */
    public function getDefaultAttributes(): array;
}
