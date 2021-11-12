<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Calculation\Business;

use Pyz\Zed\Calculation\Business\Model\Calculator\CanceledTotalCalculator;
use Pyz\Zed\Calculation\CalculationDependencyProvider;
use Pyz\Zed\Refund\Business\RefundFacadeInterface;
use Spryker\Zed\Calculation\Business\CalculationBusinessFactory as SprykerCalculationBusinessFactory;

class CalculationBusinessFactory extends SprykerCalculationBusinessFactory
{
    /**
     * @return \Pyz\Zed\Calculation\Business\Model\Calculator\CanceledTotalCalculator
     */
    public function createCanceledTotalCalculator(): CanceledTotalCalculator
    {
        return new CanceledTotalCalculator(
            $this->getRefundFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Business\RefundFacadeInterface
     */
    protected function getRefundFacade(): RefundFacadeInterface
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::FACADE_REFUND);
    }
}
