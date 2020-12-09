<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business;

use Pyz\Zed\SalesOrderUid\Business\OrderExpander\OrderExpander;
use Pyz\Zed\SalesOrderUid\Business\OrderExpander\OrderExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig getConfig()
 */
class SalesOrderUidBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\SalesOrderUid\Business\OrderExpander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->getConfig());
    }
}
