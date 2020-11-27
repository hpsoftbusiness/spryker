<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;

class AssignedProductListTable extends AbstractProductListAssignmentTable
{
    /**
     * @var string
     */
    protected $defaultUrl = 'assigned-product-list-table';

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected function getQuery(): SpyProductListQuery
    {
        return $this->tableQueryBuilder->buildAssignedQuery($this->idCustomerGroup);
    }

    /**
     * @return string
     */
    protected function getCheckboxCheckedAttribute(): string
    {
        return 'checked="checked"';
    }
}
