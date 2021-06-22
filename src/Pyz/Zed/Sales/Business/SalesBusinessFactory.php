<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business;

use Pyz\Zed\Sales\Business\Order\OrderHydrator as OrderHydratorWithMultiShippingAddress;
use Pyz\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapper;
use Pyz\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\Business\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Business\SalesBusinessFactory as SprykerSalesBusinessFactory;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface getEntityManager()
 */
class SalesBusinessFactory extends SprykerSalesBusinessFactory
{
    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    public function createOrderHydratorWithMultiShippingAddress(): OrderHydratorInterface
    {
        return new OrderHydratorWithMultiShippingAddress(
            $this->getQueryContainer(),
            $this->getOmsFacade(),
            $this->getConfig(),
            $this->getCustomerFacade(),
            $this->getHydrateOrderPlugins(),
            $this->getOrderItemExpanderPlugins(),
            $this->getCustomerOrderAccessCheckPlugins(),
            $this->getOrderExpenseExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    public function createOrderForExportHydrator(): OrderHydratorInterface
    {
        return new OrderHydratorWithMultiShippingAddress(
            $this->getQueryContainer(),
            $this->getOmsFacade(),
            $this->getConfig(),
            $this->getCustomerFacade(),
            $this->getOrderForExportExpanderPlugins(),
            $this->getOrderItemForExportExpanderPlugins(),
            []
        );
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[]
     */
    public function getOrderForExportExpanderPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_FOR_EXPORT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[]
     */
    public function getOrderItemForExportExpanderPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_ITEM_FOR_EXPORT_EXPANDER);
    }

    /**
     * @return \Pyz\Zed\Sales\Dependency\Plugin\OrderExpenseExpanderPluginInterface[]
     */
    public function getOrderExpenseExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_EXPENSE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    public function createSalesOrderItemMapper(): SalesOrderItemMapperInterface
    {
        return new SalesOrderItemMapper();
    }
}
