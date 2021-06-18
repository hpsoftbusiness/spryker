<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage;

use Spryker\Client\ProductStorage\ProductStorageFactory as SprykerProductStorageFactory;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;

/**
 * @method \Pyz\Client\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageFactory extends SprykerProductStorageFactory
{
    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverter
     */
    public function createDecimalToIntegerConverter()
    {
        return new DecimalToIntegerConverter();
    }
}
