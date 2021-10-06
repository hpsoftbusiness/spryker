<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage;

use Pyz\Client\ProductStorage\Filter\ProductAbstractAttributeMapWithoutRestrictionFilter;
use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;
use Spryker\Client\ProductStorage\ProductStorageFactory as SprykerProductStorageFactory;
use Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReader;
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

    /**
     * @return \Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReaderInterface
     */
    public function createProductAbstractStorageReaderWithoutRestrictions()
    {
        return new ProductAbstractStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStoreClient(),
            $this->createProductAbstractAttributeMapWithoutRestrictionFilter(),
            [],
            []
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface
     */
    public function createProductAbstractAttributeMapWithoutRestrictionFilter(): ProductAbstractAttributeMapRestrictionFilterInterface
    {
        return new ProductAbstractAttributeMapWithoutRestrictionFilter();
    }
}
