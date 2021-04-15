<?php

namespace Pyz\Client\ProductListStorage\ProductConcreteRestriction;

use Pyz\Client\ProductList\ProductListClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader as SprykerProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;

class ProductConcreteRestrictionReader extends SprykerProductConcreteRestrictionReader
{
    /**
     * @var ProductListClientInterface
     */
    protected $productListClient;

    /**
     * @param ProductListStorageToCustomerClientInterface $customerClient
     * @param ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader
     * @param ProductListClientInterface $productListClient
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader,
        ProductListClientInterface $productListClient
    ) {
        parent::__construct($customerClient, $productListProductConcreteStorageReader);
        $this->productListClient = $productListClient;
    }

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProduct): bool
    {
        $customer = $this->customerClient->getCustomer();
        if ($customer) {
            $customerProductListCollectionTransfer = $customer->getCustomerProductListCollection();
        } else {
            $customerProductListCollectionTransfer = $this->productListClient->getDefaultCustomerProductListCollection();
        }

        if (!$customerProductListCollectionTransfer) {
            return false;
        }

        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];

        return $this->isProductConcreteRestrictedInProductLists($idProduct, $customerWhitelistIds, $customerBlacklistIds);
    }
}
