<?php

namespace Pyz\Client\ProductListStorage\ProductAbstractRestriction;

use Pyz\Client\ProductList\ProductListClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReader as SprykerProductAbstractRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface;

class ProductAbstractRestrictionReader extends SprykerProductAbstractRestrictionReader
{
    /**
     * @var ProductListClientInterface
     */
    protected $productListClient;

    /**
     * @param ProductListStorageToCustomerClientInterface $customerClient
     * @param ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader
     * @param ProductListClientInterface $productListClient
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader,
        ProductListClientInterface $productListClient
    ) {
        parent::__construct($customerClient, $productListProductAbstractStorageReader);
        $this->productListClient = $productListClient;
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
            $customerProductListCollectionTransfer = $this->productListClient->getDefaultCustomerProductListCollection();
        }

        if (!$customerProductListCollectionTransfer) {
            return false;
        }

        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];

        return $this->checkIfProductAbstractIsRestricted($idProductAbstract, $customerWhitelistIds, $customerBlacklistIds);
    }
}
