<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage\ProductAbstractRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Shared\CustomerGroup\CustomerGroupConstants;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReader as SprykerProductAbstractRestrictionReader;

class ProductAbstractRestrictionReader extends SprykerProductAbstractRestrictionReader
{
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
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
        $customerProductListCollectionTransfer->addWhitelistId(CustomerGroupConstants::ID_CUSTOMER_MW);

        return $customerProductListCollectionTransfer;
    }
}
