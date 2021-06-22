<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication;

use Pyz\Zed\Refund\RefundDependencyProvider;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Refund\Communication\RefundCommunicationFactory as SprykerRefundCommunicationFactory;

class RefundCommunicationFactory extends SprykerRefundCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface[]
     */
    public function getRefundCalculatorPlugins(): array
    {
        return [
            $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_ITEM_REFUND_CALCULATOR),
            $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_EXPENSE_REFUND_CALCULATOR),
        ];
    }
}
