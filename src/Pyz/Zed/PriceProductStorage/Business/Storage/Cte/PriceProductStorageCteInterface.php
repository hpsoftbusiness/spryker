<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage\Cte;

interface PriceProductStorageCteInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array;

    /**
     * @return string
     */
    public function getSql(): string;
}
