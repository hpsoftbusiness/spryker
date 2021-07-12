<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Turnover\Business;

use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Pyz\Zed\Turnover\Business\Model\TurnoverCalculator;
use Pyz\Zed\Turnover\Communication\Plugin\TurnoverCalculatorPlugin;
use Pyz\Zed\Turnover\TurnoverDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class TurnoverBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\Turnover\Business\Model\TurnoverCalculator
     */
    public function createTurnoverCalculator(): TurnoverCalculator
    {
        return new TurnoverCalculator(
            $this->createTurnoverCalculatorPluginList(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Turnover\Communication\Plugin\TurnoverCalculatorPlugin[]
     */
    private function createTurnoverCalculatorPluginList(): array
    {
        return [
            new TurnoverCalculatorPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(TurnoverDependencyProvider::FACADE_SALES);
    }
}
