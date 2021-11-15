<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Table;

use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Sales\Communication\Table\CustomerOrdersTable as SprykerCustomerOrdersTable;
use Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilderInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CustomerOrdersTable extends SprykerCustomerOrdersTable
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToUserInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilderInterface $queryBuilder
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeInterface $sanitizeService
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface $customerFacade
     * @param string $customerReference
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToUserInterface $userFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface[] $salesTablePlugins
     */
    public function __construct(
        OrdersTableQueryBuilderInterface $queryBuilder,
        SalesToMoneyInterface $moneyFacade,
        SalesToUtilSanitizeInterface $sanitizeService,
        UtilDateTimeServiceInterface $utilDateTimeService,
        SalesToCustomerInterface $customerFacade,
        $customerReference,
        SalesQueryContainerInterface $salesQueryContainer,
        SalesToUserInterface $userFacade,
        array $salesTablePlugins = []
    ) {
        parent::__construct(
            $queryBuilder,
            $moneyFacade,
            $sanitizeService,
            $utilDateTimeService,
            $customerFacade,
            $customerReference,
            $salesQueryContainer,
            $salesTablePlugins
        );
        $this->userFacade = $userFacade;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function buildQuery()
    {
        $query = parent::buildQuery();
        $userTransfer = $this->userFacade->getCurrentUser();
        if ($userTransfer->getFkStore()) {
            $query->filterByStore($userTransfer->getStoreName());
        }

        return $query;
    }
}
