<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\Process;

use Spryker\Zed\Oms\Business\Process\EventInterface as SprykerEventInterface;

interface EventInterface extends SprykerEventInterface
{
    /**
     * @param string|null $condition
     *
     * @return void
     */
    public function setCondition(?string $condition): void;

    /**
     * @return string|null
     */
    public function getCondition(): ?string;
}
