<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage\ProductConcreteRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface;
use Pyz\Shared\CustomerGroup\CustomerGroupConstants;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader as SprykerProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;

class ProductConcreteRestrictionReader extends SprykerProductConcreteRestrictionReader
{
    /**
     * @var \Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface
     */
    protected $customerGroupStorageClient;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader
     * @param \Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface $customerGroupStorageClient
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader,
        CustomerGroupStorageClientInterface $customerGroupStorageClient
    ) {
        parent::__construct($customerClient, $productListProductConcreteStorageReader);
        $this->customerGroupStorageClient = $customerGroupStorageClient;
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
        return $this
            ->customerGroupStorageClient
            ->getCustomerProductListCollectionByIdCustomerGroup(
                CustomerGroupConstants::ID_CUSTOMER_MW
            );
    }
}
