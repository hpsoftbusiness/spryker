<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business;

use Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculator;
use Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculatorInterface;
use Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculator;
use Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculatorInterface;
use Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculator;
use Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculatorInterface;
use Pyz\Zed\Refund\Business\Model\ExternalPaymentRemover;
use Pyz\Zed\Refund\Business\Model\ExternalPaymentRemoverInterface;
use Pyz\Zed\Refund\Business\Model\RefundCalculator;
use Pyz\Zed\Refund\Business\Model\RefundCalculator\ExpenseRefundCalculator;
use Pyz\Zed\Refund\Business\Model\RefundCalculator\ItemRefundCalculator;
use Pyz\Zed\Refund\Business\Model\RefundCalculatorInterface as ModelRefundCalculatorInterface;
use Pyz\Zed\Refund\Business\Model\RefundSaver;
use Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregator;
use Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregatorInterface;
use Pyz\Zed\Refund\Business\Processor\RefundProcessor;
use Pyz\Zed\Refund\Business\Processor\RefundProcessorInterface;
use Pyz\Zed\Refund\Business\RefundDetails\RefundDetailsCollector;
use Pyz\Zed\Refund\Business\RefundDetails\RefundDetailsCollectorInterface;
use Pyz\Zed\Refund\Business\Validator\RefundValidator;
use Pyz\Zed\Refund\Business\Validator\RefundValidatorInterface;
use Pyz\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\RefundCalculatorInterface as SpecifiedRefundCalculatorInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaverInterface;
use Spryker\Zed\Refund\Business\RefundBusinessFactory as SprykerRefundBusinessFactory;

/**
 * @method \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface getRepository()()
 */
class RefundBusinessFactory extends SprykerRefundBusinessFactory
{
    /**
     * @return \Pyz\Zed\Refund\Business\RefundDetails\RefundDetailsCollectorInterface
     */
    public function createRefundDetailsCollector(): RefundDetailsCollectorInterface
    {
        return new RefundDetailsCollector(
            $this->getSalesFacade(),
            $this->getRefundDetailsCollectorPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Processor\RefundProcessorInterface
     */
    public function createRefundProcessor(): RefundProcessorInterface
    {
        return new RefundProcessor(
            $this->getSalesFacade(),
            $this->createPaymentRefundAggregator(),
            $this->getEntityManager(),
            $this->getRefundProcessorPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregatorInterface
     */
    public function createPaymentRefundAggregator(): PaymentRefundsAggregatorInterface
    {
        return new PaymentRefundsAggregator();
    }

    /**
     * @return \Spryker\Zed\Refund\Business\Model\RefundSaverInterface
     */
    public function createRefundSaver(): RefundSaverInterface
    {
        return new RefundSaver(
            $this->getSalesQueryContainer(),
            $this->getSalesFacade(),
            $this->getCalculationFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Model\RefundCalculatorInterface
     */
    public function createRefundCalculator(): ModelRefundCalculatorInterface
    {
        return new RefundCalculator(
            $this->getRefundCalculatorPlugins(),
            $this->getSalesFacade(),
            $this->createRefundablePaymentCalculator(),
            $this->createExternalPaymentRemover()
        );
    }

    /**
     * @return \Spryker\Zed\Refund\Business\Model\RefundCalculator\RefundCalculatorInterface
     */
    public function createItemRefundCalculator(): SpecifiedRefundCalculatorInterface
    {
        return new ItemRefundCalculator($this->createItemPaymentRefundCalculator());
    }

    /**
     * @return \Spryker\Zed\Refund\Business\Model\RefundCalculator\RefundCalculatorInterface
     */
    public function createExpenseRefundCalculator(): SpecifiedRefundCalculatorInterface
    {
        return new ExpenseRefundCalculator($this->createExpensePaymentRefundCalculator());
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculatorInterface
     */
    public function createItemPaymentRefundCalculator(): ItemPaymentRefundCalculatorInterface
    {
        return new ItemPaymentRefundCalculator($this->getItemPaymentRefundCalculatorPlugins());
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculatorInterface
     */
    public function createExpensePaymentRefundCalculator(): ExpensePaymentRefundCalculatorInterface
    {
        return new ExpensePaymentRefundCalculator($this->getExpensePaymentRefundCalculatorPlugins());
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculatorInterface
     */
    public function createRefundablePaymentCalculator(): RefundablePaymentCalculatorInterface
    {
        return new RefundablePaymentCalculator($this->getRepository());
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Validator\RefundValidatorInterface
     */
    public function createRefundValidator(): RefundValidatorInterface
    {
        return new RefundValidator(
            $this->getSalesFacade(),
            $this->getEntityManager(),
            $this->getRefundValidatorPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\Refund\Business\Model\ExternalPaymentRemoverInterface
     */
    protected function createExternalPaymentRemover(): ExternalPaymentRemoverInterface
    {
        return new ExternalPaymentRemover(
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface|\Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\RefundValidatorPluginInterface[]
     */
    private function getRefundValidatorPlugins(): array
    {
        return $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_REFUND_VALIDATOR);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\ExpenseRefundCalculatorPluginInterface[]
     */
    private function getExpensePaymentRefundCalculatorPlugins(): array
    {
        return $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_EXPENSE_PAYMENT_REFUND_CALCULATOR);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface[]
     */
    private function getRefundProcessorPlugins(): array
    {
        return $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_REFUND_PROCESSOR);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\RefundDetailsCollectorPluginInterface[]
     */
    private function getRefundDetailsCollectorPlugins(): array
    {
        return $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_REFUND_DETAILS_COLLECTOR);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\ItemRefundCalculatorPluginInterface[]
     */
    private function getItemPaymentRefundCalculatorPlugins(): array
    {
        return $this->getProvidedDependency(RefundDependencyProvider::PLUGIN_ITEM_PAYMENT_REFUND_CALCULATOR);
    }
}
