<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander;

interface ReaderExpanderInterface
{
    /**
     * @param array $catalogSearchResult
     *
     * @return array
     */
    public function expand(array $catalogSearchResult): array;
}
