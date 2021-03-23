<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListGui\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductListGui\Communication\Table\AvailableProductConcreteTable as SprykerAvailableProductConcreteTable;

class AvailableProductConcreteTable extends SprykerAvailableProductConcreteTable
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $spyProductQuery = $this->buildQuery();

        $queryResults = $this->runQuery($spyProductQuery->filterByIsRemoved(false), $config);

        $results = [];
        foreach ($queryResults as $productData) {
            $results[] = $this->buildDataRow($productData);
        }
        unset($queryResults);

        return $results;
    }
}
