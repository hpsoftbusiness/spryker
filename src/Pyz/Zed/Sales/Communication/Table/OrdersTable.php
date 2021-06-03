<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Table;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Sales\Communication\Table\OrdersTable as SprykerOrdersTable;
use Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilder;

class OrdersTable extends SprykerOrdersTable
{
    private const EXPORT_URL = '/sales/index/export';
    private const STATE_FILTER = 'id-state';

    /**
     * @return array
     */
    public function fetchData()
    {
        $wrapperArray = parent::fetchData();

        if ($search = $this->request->get('search')) {
            /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
            $session = $this->getApplicationContainer()->get('session');
            $session->set('search', $search);
        }

        if ($this->request->getPathInfo() === self::EXPORT_URL) {
            $exportData = $this->prepareDataForExport($this->config);
            $wrapperArray['exportData'] = $exportData;
        }

        return $wrapperArray;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config = parent::configure($config);
        $url = (string)Url::generate(
            '/table',
            $this->getRequest()->query->all()
        );

        $config->setUrl($url);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        return $this->formatQueryData($this->getQueryResults($config));
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareDataForExport(TableConfiguration $config): array
    {
        return $this->formatQueryDataForExport($this->getQueryResults($config));
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function getQueryResults(TableConfiguration $config): array
    {
        $query = $this->buildQuery();
        $idState = $this->request->query->getInt(self::STATE_FILTER);

        if ($idState > 0) {
            $query->filterByIdItemState($idState);
        }

        return $this->runQuery($query, $config);
    }

    /**
     * @param array $queryResults
     *
     * @return array
     */
    protected function formatQueryDataForExport(array $queryResults)
    {
        $results = [];
        foreach ($queryResults as $item) {
            $itemLine = [
                SpySalesOrderTableMap::COL_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
                SpySalesOrderTableMap::COL_ORDER_REFERENCE => $item[SpySalesOrderTableMap::COL_ORDER_REFERENCE],
                SpySalesOrderTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySalesOrderTableMap::COL_CREATED_AT]),
                SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE => $this->formatCustomerForExport($item),
                SpySalesOrderTableMap::COL_EMAIL => $item[SpySalesOrderTableMap::COL_EMAIL],
                static::ITEM_STATE_NAMES_CSV => $this->groupItemStateNamesForExport($item[OrdersTableQueryBuilder::FIELD_ITEM_STATE_NAMES_CSV]),
                static::GRAND_TOTAL => $this->getGrandTotal($item),
                static::NUMBER_OF_ORDER_ITEMS => $item[OrdersTableQueryBuilder::FIELD_NUMBER_OF_ORDER_ITEMS],
            ];
            $results[] = $itemLine;
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function formatCustomerForExport(array $item)
    {
        $salutation = $item[SpySalesOrderTableMap::COL_SALUTATION];

        $customer = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $item[SpySalesOrderTableMap::COL_FIRST_NAME],
            $item[SpySalesOrderTableMap::COL_LAST_NAME]
        );

        return $this->sanitizeService->escapeHtml($customer);
    }

    /**
     * @param string $itemStateNamesCsv
     *
     * @return string
     */
    protected function groupItemStateNamesForExport($itemStateNamesCsv)
    {
        $itemStateNames = explode(',', $itemStateNamesCsv);
        $itemStateNames = array_map('trim', $itemStateNames);
        $distinctItemStateNames = array_unique($itemStateNames);

        return implode(', ', $distinctItemStateNames);
    }
}
