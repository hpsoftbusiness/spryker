<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Pyz\Zed\CustomerGroup\Communication\Form\CustomerGroupForm;
use Pyz\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider;
use Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignedProductListTable;
use Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentProductListQueryBuilder;
use Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentProductListQueryBuilderInterface;
use Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AvailableProductListTable;
use Pyz\Zed\CustomerGroup\Communication\Table\ProductListTable;
use Pyz\Zed\CustomerGroup\Communication\Tabs\CustomerGroupFormTabs;
use Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory as SprykerCustomerGroupCommunicationFactory;

/**
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 */
class CustomerGroupCommunicationFactory extends SprykerCustomerGroupCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createCustomerGroupFormTabs()
    {
        return new CustomerGroupFormTabs();
    }

    /**
     * @return \Pyz\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider
     */
    public function createCustomerGroupFormDataProvider()
    {
        return new CustomerGroupFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerGroupForm(CustomerGroupTransfer $data, array $options = [])
    {
        $options[CustomerGroupForm::ID_CUSTOMER_GROUP] = $data->getIdCustomerGroup();

        return $this->getFormFactory()->create(CustomerGroupForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Pyz\Zed\CustomerGroup\Communication\Table\ProductListTable
     */
    public function createProductListTable(CustomerGroupTransfer $customerGroupTransfer): ProductListTable
    {
        return new ProductListTable(
            $this->getQueryContainer(),
            $customerGroupTransfer
        );
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AvailableProductListTable
     */
    public function createAvailableProductListTable(?int $idCustomerGroup = null): AvailableProductListTable
    {
        return new AvailableProductListTable(
            $this->createAssignmentProductListQueryBuilder(),
            $this->getUtilEncodingService(),
            $idCustomerGroup
        );
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignedProductListTable
     */
    public function createAssignedProductListTable(?int $idCustomerGroup = null): AssignedProductListTable
    {
        return new AssignedProductListTable(
            $this->createAssignmentProductListQueryBuilder(),
            $this->getUtilEncodingService(),
            $idCustomerGroup
        );
    }

    /**
     * @return \Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentProductListQueryBuilderInterface
     */
    public function createAssignmentProductListQueryBuilder(): AssignmentProductListQueryBuilderInterface
    {
        return new AssignmentProductListQueryBuilder();
    }
}
