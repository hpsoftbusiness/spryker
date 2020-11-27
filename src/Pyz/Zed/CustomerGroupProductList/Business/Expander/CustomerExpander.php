<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Business\Expander;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    /**
     * @var \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface
     */
    protected $customerGroupProductListRepository;

    /**
     * @param \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface $customerGroupProductListRepository
     */
    public function __construct(
        CustomerGroupProductListRepositoryInterface $customerGroupProductListRepository
    ) {
        $this->customerGroupProductListRepository = $customerGroupProductListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductListIds(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer->requireIdCustomer();

        $productListTransfers = $this->customerGroupProductListRepository
            ->getProductListTransfersByIdCustomer(
                $customerTransfer->getIdCustomer()
            );

        $customerTransfer->setCustomerProductListCollection(new CustomerProductListCollectionTransfer());
        $this->addProductListsToCustomerTransfer($customerTransfer, $productListTransfers);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductListTransfer[] $productListTransfers
     *
     * @return void
     */
    protected function addProductListsToCustomerTransfer(
        CustomerTransfer $customerTransfer,
        array $productListTransfers
    ): void {
        foreach ($productListTransfers as $productListTransfer) {
            if ($productListTransfer->getType() === 'whitelist') {
                $customerTransfer->getCustomerProductListCollection()
                    ->addWhitelistId($productListTransfer->getIdProductList());

                continue;
            }

            $customerTransfer->getCustomerProductListCollection()
                ->addBlacklistId($productListTransfer->getIdProductList());
        }
    }
}
