<?php

namespace Pyz\Client\ProductListStorage;

use Pyz\Client\ProductList\ProductListClientInterface;
use Pyz\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReader;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReaderInterface;
use Pyz\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface;
use Spryker\Client\ProductListStorage\ProductListStorageFactory as SprykerProductListStorageFactory;

class ProductListStorageFactory extends SprykerProductListStorageFactory
{
    /**
     * @return \Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReaderInterface
     */
    public function createProductAbstractRestrictionReader(): ProductAbstractRestrictionReaderInterface
    {
        return new ProductAbstractRestrictionReader(
            $this->getCustomerClient(),
            $this->createProductListProductAbstractStorageReader(),
            $this->getProductListClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface
     */
    public function createProductConcreteRestrictionReader(): ProductConcreteRestrictionReaderInterface
    {
        return new ProductConcreteRestrictionReader(
            $this->getCustomerClient(),
            $this->createProductListProductConcreteStorageReader(),
            $this->getProductListClient()
        );
    }

    /**
     * @return ProductListClientInterface
     */
    public function getProductListClient(): ProductListClientInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::CLIENT_PRODUCT_LIST);
    }
}
