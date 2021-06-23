<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Dependency\Plugin;

interface InternalDiscountPluginInterface
{
    /**
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * @return string
     */
    public function getCalculatorPluginName(): string;

    /**
     * @return string
     */
    public function getCollectorPluginName(): string;
}
