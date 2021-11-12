<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund;

use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundCalculatorPlugin;
use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundProcessorPlugin;
use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundValidatorPlugin;
use Pyz\Zed\DummyPrepayment\Communication\Plugin\Refund\DummyPrepaymentRefundCalculatorPlugin;
use Pyz\Zed\DummyPrepayment\Communication\Plugin\Refund\DummyPrepaymentRefundProcessorPlugin;
use Pyz\Zed\DummyPrepayment\Communication\Plugin\Refund\DummyPrepaymentRefundValidatorPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\BenefitVoucherRefundCalculatorPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\CashbackRefundCalculatorPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\EVoucherRefundCalculatorPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\MarketerEVoucherRefundCalculatorPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund\MyWorldRefundProcessorPlugin;
use Pyz\Zed\Refund\Communication\Plugin\ExpenseRefundDetailsCollectorPlugin;
use Pyz\Zed\Refund\Communication\Plugin\ItemRefundDetailsCollectorPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\RefundDependencyProvider as SprykerRefundDependencyProvider;

class RefundDependencyProvider extends SprykerRefundDependencyProvider
{
    public const PLUGIN_REFUND_DETAILS_COLLECTOR = 'PLUGIN_REFUND_DETAILS_COLLECTOR';
    public const PLUGIN_ITEM_PAYMENT_REFUND_CALCULATOR = 'PLUGIN_ITEM_PAYMENT_REFUND_CALCULATOR';
    public const PLUGIN_EXPENSE_PAYMENT_REFUND_CALCULATOR = 'PLUGIN_EXPENSE_PAYMENT_REFUND_CALCULATOR';
    public const PLUGIN_REFUND_PROCESSOR = 'PLUGIN_REFUND_PROCESSOR';
    public const PLUGIN_REFUND_VALIDATOR = 'PLUGIN_REFUND_VALIDATOR';
    public const FACADE_ACL = 'FACADE_ACL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addRefundDetailsCollectorPlugins($container);
        $this->addItemPaymentRefundCalculatorPlugins($container);
        $this->addExpensePaymentRefundCalculatorPlugins($container);
        $this->addRefundProcessorPlugins($container);
        $this->addRefundValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $this->addSalesFacade($container);
        $this->addAclFacade($container);
        $this->addItemRefundCalculatorPlugin($container);
        $this->addExpenseRefundCalculatorPlugin($container);
        $this->addCalculationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(self::FACADE_SALES, static function (Container $container) {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclFacade(Container $container): Container
    {
        $container->set(self::FACADE_ACL, static function (Container $container) {
            return $container->getLocator()->acl()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addRefundDetailsCollectorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_REFUND_DETAILS_COLLECTOR, function () {
            return [
                new ItemRefundDetailsCollectorPlugin(),
                new ExpenseRefundDetailsCollectorPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addItemPaymentRefundCalculatorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_ITEM_PAYMENT_REFUND_CALCULATOR, function () {
            return [
                // Plugins HAVE to be ordered in descending order by priority.
                new BenefitVoucherRefundCalculatorPlugin(),
                new AdyenRefundCalculatorPlugin(),
                new DummyPrepaymentRefundCalculatorPlugin(),
                new CashbackRefundCalculatorPlugin(),
                new EVoucherRefundCalculatorPlugin(),
                new MarketerEVoucherRefundCalculatorPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addExpensePaymentRefundCalculatorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_EXPENSE_PAYMENT_REFUND_CALCULATOR, function () {
            return [
                // Plugins HAVE to be ordered in descending order by priority.
                new AdyenRefundCalculatorPlugin(),
                new DummyPrepaymentRefundCalculatorPlugin(),
                new CashbackRefundCalculatorPlugin(),
                new EVoucherRefundCalculatorPlugin(),
                new MarketerEVoucherRefundCalculatorPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addRefundProcessorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_REFUND_PROCESSOR, function () {
            return [
                new MyWorldRefundProcessorPlugin(),
                new AdyenRefundProcessorPlugin(),
                new DummyPrepaymentRefundProcessorPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addRefundValidatorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_REFUND_VALIDATOR, function () {
            return [
                new AdyenRefundValidatorPlugin(),
                new DummyPrepaymentRefundValidatorPlugin(),
            ];
        });
    }
}
