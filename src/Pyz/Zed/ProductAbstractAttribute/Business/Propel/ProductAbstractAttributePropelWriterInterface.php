<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Business\Propel;

interface ProductAbstractAttributePropelWriterInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function save(array $productAbstractIds);
}
