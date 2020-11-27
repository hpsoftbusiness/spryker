<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Business;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListBusinessFactory getFactory()
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListEntityManagerInterface getEntityManager()()
 */
class CustomerGroupProductListFacade extends AbstractFacade implements CustomerGroupProductListFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductListIds(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createCustomerExpander()
            ->expandCustomerTransferWithProductListIds($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToProductListTransfer
     */
    public function createCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): CustomerGroupToProductListTransfer {
        return $this->getEntityManager()->createCustomerGroupProductList($customerGroupToProductListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return void
     */
    public function deleteCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): void {
        $this->getEntityManager()->deleteCustomerGroupProductList($customerGroupToProductListTransfer);
    }
}
