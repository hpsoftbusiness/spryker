<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroupStorage;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;

interface CustomerGroupStorageClientInterface
{
    /**
     * Returns customer product list collection by given customer group id
     *
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getCustomerProductListCollectionByIdCustomerGroup(int $idCustomerGroup): CustomerProductListCollectionTransfer;
}
