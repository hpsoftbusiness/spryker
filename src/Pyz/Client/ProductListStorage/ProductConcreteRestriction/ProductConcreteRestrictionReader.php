<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage\ProductConcreteRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Shared\CustomerGroup\CustomerGroupConstants;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader as SprykerProductConcreteRestrictionReader;

class ProductConcreteRestrictionReader extends SprykerProductConcreteRestrictionReader
{
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
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
        $customerProductListCollectionTransfer->addWhitelistId(CustomerGroupConstants::ID_CUSTOMER_MW);

        return $customerProductListCollectionTransfer;
    }
}
