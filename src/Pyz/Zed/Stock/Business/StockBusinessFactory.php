<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business;

use Pyz\Zed\Stock\Business\Expander\OrderExpander;
use Pyz\Zed\Stock\Business\Expander\OrderExpanderInterface;
use Pyz\Zed\Stock\Business\Model\Writer;
use Pyz\Zed\Stock\Business\Model\WriterInterface;
use Spryker\Zed\Stock\Business\StockBusinessFactory as SprykerStockBusinessFactory;

/**
 * @method \Pyz\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 */
class StockBusinessFactory extends SprykerStockBusinessFactory
{
    /**
     * @return \Pyz\Zed\Stock\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Pyz\Zed\Stock\Business\Model\WriterInterface
     */
    public function createWriterModel(): WriterInterface
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->createStockReader(),
            $this->createStockProductReader(),
            $this->getTouchFacade(),
            $this->getStockUpdateHandlerPlugins()
        );
    }
}
