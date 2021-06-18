<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Business\CustomerGroup;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;

class CustomerGroupReader
{
    /**
     * @var \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductList\Business\ProductListFacadeInterface $productListFacade
     */
    public function __construct(
        CustomerGroupStorageRepositoryInterface $repository,
        ProductListFacadeInterface $productListFacade
    ) {
        $this->repository = $repository;
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function findCustomerGroupById(int $idCustomerGroup): CustomerGroupTransfer
    {
        $customerGroupTransfer = $this
            ->repository
            ->findCustomerGroupByIdCustomerGroup($idCustomerGroup);

        foreach ($customerGroupTransfer->getProductLists() as $customerGroupToProductList) {
            $productListTransfer = new ProductListTransfer();
            $productListTransfer->setIdProductList(
                $customerGroupToProductList->getIdProductList()
            );
            $productListTransfer = $this->productListFacade->getProductListById($productListTransfer);
            $productListTransfer->setProductListCategoryRelation(null);
            $productListTransfer->setProductListProductConcreteRelation(null);
            $customerGroupToProductList->setProductList(
                $productListTransfer
            );
        }

        return $customerGroupTransfer;
    }
}
