<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListPersistenceFactory getFactory()
 */
class CustomerGroupProductListRepository extends AbstractRepository implements CustomerGroupProductListRepositoryInterface
{
    /**
     * @module ProductList
     * @module Customer
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer[]
     */
    public function getProductListTransfersByIdCustomer(int $idCustomer): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductList[] $productListEntities */
        $productListEntities = $this->getFactory()
            ->getProductListQuery()
            ->usePyzCustomerGroupToProductListQuery()
                ->useCustomerGroupQuery()
                    ->useSpyCustomerGroupToCustomerQuery()
                        ->filterByFkCustomer($idCustomer)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        $customerGroupProductListMapper = $this->getFactory()->createCustomerGroupProductListMapper();
        $productListTransfers = [];

        foreach ($productListEntities as $productListEntity) {
            $productListTransfers[] = $customerGroupProductListMapper->mapProductListEntityToProductListTransfer(
                $productListEntity,
                new ProductListTransfer()
            );
        }

        return $productListTransfers;
    }
}
