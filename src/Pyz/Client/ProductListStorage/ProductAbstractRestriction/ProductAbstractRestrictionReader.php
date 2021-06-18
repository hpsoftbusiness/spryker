<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage\ProductAbstractRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface;
use Pyz\Shared\CustomerGroup\CustomerGroupConstants;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReader as SprykerProductAbstractRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface;

class ProductAbstractRestrictionReader extends SprykerProductAbstractRestrictionReader
{
    /**
     * @var \Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface
     */
    protected $customerGroupStorageClient;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader
     * @param \Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface $customerGroupStorageClient
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader,
        CustomerGroupStorageClientInterface $customerGroupStorageClient
    ) {
        parent::__construct($customerClient, $productListProductAbstractStorageReader);
        $this->customerGroupStorageClient = $customerGroupStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
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

        return $this->checkIfProductAbstractIsRestricted($idProductAbstract, $customerWhitelistIds, $customerBlacklistIds);
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
