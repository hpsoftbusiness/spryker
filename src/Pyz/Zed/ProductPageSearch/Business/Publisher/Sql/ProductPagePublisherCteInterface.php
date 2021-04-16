<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher\Sql;

interface ProductPagePublisherCteInterface
{
    /**
     * @return string
     */
    public function getSql(): string;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array;
}
