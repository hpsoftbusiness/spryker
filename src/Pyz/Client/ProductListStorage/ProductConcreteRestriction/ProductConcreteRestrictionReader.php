<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage\ProductConcreteRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Client\ProductList\ProductListClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader as SprykerProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;

class ProductConcreteRestrictionReader extends SprykerProductConcreteRestrictionReader
{
    /**
     * @var \Pyz\Client\ProductList\ProductListClientInterface
     */
    protected $productListClient;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader
     * @param \Pyz\Client\ProductList\ProductListClientInterface $productListClient
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
//            $customerProductListCollectionTransfer = $this->productListClient->getDefaultCustomerProductListCollection();
            $customerProductListCollectionTransfer = $this->getDefaultCustomerProductListCollection();
        }

        if (!$customerProductListCollectionTransfer) {
            return false;
        }

        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];

        return $this->isProductConcreteRestrictedInProductLists($idProduct, $customerWhitelistIds, $customerBlacklistIds);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
//        TODO:: investigate and uncomment
//        $customerProductListCollectionTransfer->addWhitelistId(CustomerGroupConstants::ID_CUSTOMER_MW);

        return $customerProductListCollectionTransfer;
    }
}
