<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms;

use Pyz\Zed\Adyen\Communication\Plugin\Oms\Command\AdyenRefundCommandByOrderPlugin;
use Pyz\Zed\MyWorldMarketplaceApi\Communication\Plugin\Oms\Command\CancelTurnoverCommandByOrderPlugin;
use Pyz\Zed\MyWorldMarketplaceApi\Communication\Plugin\Oms\Command\CreateTurnoverCommandByOrderPlugin;
use Pyz\Zed\MyWorldMarketplaceApi\Communication\Plugin\Oms\Condition\IsTurnoverCancelledConditionPlugin;
use Pyz\Zed\MyWorldMarketplaceApi\Communication\Plugin\Oms\Condition\IsTurnoverCreatedConditionPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Oms\Condition\IsMyWorldPaymentInitiated;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Oms\Condition\IsMyWorldPaymentProcessed;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Oms\Condition\IsMyWorldPaymentUsed;
use Pyz\Zed\Oms\Communication\Plugin\Oms\Command\RefundCalculationCommandByOrderPlugin;
use Pyz\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderInProcessingPlugin;
use Pyz\Zed\Oms\Communication\Plugin\Oms\Command\SendShippingConfirmationPlugin;
use Pyz\Zed\Oms\Communication\Plugin\Oms\Condition\Is1HourNotPassedConditionPlugin;
use Pyz\Zed\Oms\Communication\Plugin\Oms\Condition\TrueConditionPlugin;
use Pyz\Zed\Oms\Communication\Plugin\Oms\InitiationTimeoutProcessorPlugin;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Command\ManualRefundCommand;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Command\MyWorldRefundOnFailedPaymentCommand;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Command\ProcessRefundCommand;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Command\ValidateRefundCommand;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Condition\IsAuthorizedToRefundCondition;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Condition\IsFailedCondition;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Condition\IsPendingCondition;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Condition\IsPossibleToRefundAfterClosingCondition;
use Pyz\Zed\Refund\Communication\Plugin\Oms\Condition\IsRefundedCondition;
use Pyz\Zed\Sales\Communication\Plugin\Command\SaveDeliveryTrackingCodeCommandPlugin;
use Pyz\Zed\Weclapp\Communication\Plugin\Oms\Command\WeclappExportOrderCommand;
use Spryker\Zed\Availability\Communication\Plugin\AvailabilityHandlerPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Command\CreateGiftCardCommandPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Condition\IsGiftCardConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Oms\Command\ShipGiftCardByEmailCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderConfirmationPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderShippedPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider as SprykerOmsDependencyProvider;
use Spryker\Zed\ProductBundle\Communication\Plugin\Oms\ProductBundleAvailabilityHandlerPlugin;
use Spryker\Zed\SalesInvoice\Communication\Plugin\Oms\GenerateOrderInvoiceCommandPlugin;
use Spryker\Zed\SalesReturn\Communication\Plugin\Oms\Command\StartReturnCommandPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentManualEventGrouperPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentOrderMailExpanderPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Command\AuthorizePlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Command\CancelOrRefundPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Command\CancelPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Command\CapturePlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsAuthorizationFailedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsAuthorizedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCanceledPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCancellationFailedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCancellationReceivedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCapturedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCaptureFailedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsCaptureReceivedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsRefundedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsRefundFailedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsRefundReceivedPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Oms\Condition\IsRefusedPlugin;

class OmsDependencyProvider extends SprykerOmsDependencyProvider
{
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';
    public const FACADE_REFUND = 'FACADE_REFUND';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->extendCommandPlugins($container);
        $container = $this->extendConditionPlugins($container);

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
        $container = $this->addTranslatorFacade($container);
        $container = $this->addRefundFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return $container->getLocator()->translator()->facade();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface[]
     */
    protected function getTimeoutProcessorPlugins(): array
    {
        return [
            new InitiationTimeoutProcessorPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container): Container
    {
        $container->extend(static::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new SendOrderConfirmationPlugin(), 'Oms/SendOrderConfirmation');
            $commandCollection->add(new SendOrderInProcessingPlugin(), 'Oms/SendOrderInProcessing');
            $commandCollection->add(new SendShippingConfirmationPlugin(), 'Oms/SendShippingConfirmation');
            $commandCollection->add(new SendOrderShippedPlugin(), 'Oms/SendOrderShipped');
            $commandCollection->add(new ShipGiftCardByEmailCommandPlugin(), 'GiftCardMailConnector/ShipGiftCard');
            $commandCollection->add(new CreateGiftCardCommandPlugin(), 'GiftCard/CreateGiftCard');
            $commandCollection->add(new StartReturnCommandPlugin(), 'Return/StartReturn');
            $commandCollection->add(new GenerateOrderInvoiceCommandPlugin(), 'Invoice/Generate');
            $commandCollection->add(new SaveDeliveryTrackingCodeCommandPlugin(), 'Sales/SaveDeliveryTrackingCode');

            // ----- Adyen
            $commandCollection->add(new AuthorizePlugin(), 'Adyen/Authorize');
            $commandCollection->add(new CancelPlugin(), 'Adyen/Cancel');
            $commandCollection->add(new CapturePlugin(), 'Adyen/Capture');
            $commandCollection->add(new AdyenRefundCommandByOrderPlugin(), 'Adyen/Refund');
            $commandCollection->add(new CancelOrRefundPlugin(), 'Adyen/CancelOrRefund');

            // ----- Turnover
            $commandCollection->add(new CreateTurnoverCommandByOrderPlugin(), 'MyWorld/CreateTurnover');
            $commandCollection->add(new CancelTurnoverCommandByOrderPlugin(), 'MyWorld/CancelTurnover');

            // ----- Refund
            $commandCollection->add(new RefundCalculationCommandByOrderPlugin(), 'Oms/RefundCalculation');
            $commandCollection->add(new ProcessRefundCommand(), 'Refund/Process');
            $commandCollection->add(new ValidateRefundCommand(), 'Refund/Validate');
            $commandCollection->add(new MyWorldRefundOnFailedPaymentCommand(), 'Refund/MyWorldOnFailedPayment');
            $commandCollection->add(new ManualRefundCommand(), 'Refund/ManualRefund');

            // ----- Weclapp
            $commandCollection->add(new WeclappExportOrderCommand(), 'Weclapp/ExportOrder');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container): Container
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsGiftCardConditionPlugin(), 'GiftCard/IsGiftCard');

            // ----- Adyen
            $conditionCollection->add(new IsAuthorizedPlugin(), 'Adyen/IsAuthorized');
            $conditionCollection->add(new IsCanceledPlugin(), 'Adyen/IsCanceled');
            $conditionCollection->add(new IsCancellationReceivedPlugin(), 'Adyen/IsCancellationReceived');
            $conditionCollection->add(new IsCancellationFailedPlugin(), 'Adyen/IsCancellationFailed');
            $conditionCollection->add(new IsCapturedPlugin(), 'Adyen/IsCaptured');
            $conditionCollection->add(new IsCaptureReceivedPlugin(), 'Adyen/IsCaptureReceived');
            $conditionCollection->add(new IsCaptureFailedPlugin(), 'Adyen/IsCaptureFailed');
            $conditionCollection->add(new IsRefundedPlugin(), 'Adyen/IsRefunded');
            $conditionCollection->add(new IsRefundReceivedPlugin(), 'Adyen/IsRefundReceived');
            $conditionCollection->add(new IsRefundFailedPlugin(), 'Adyen/IsRefundFailed');
            $conditionCollection->add(new IsAuthorizationFailedPlugin(), 'Adyen/IsAuthorizationFailed');
            $conditionCollection->add(new IsRefusedPlugin(), 'Adyen/IsRefused');
            $conditionCollection->add(new TrueConditionPlugin(), 'Oms/TrueCondition');
            $conditionCollection->add(new Is1HourNotPassedConditionPlugin(), 'Oms/Is1HourNotPassed');

            // ----- Turnover
            $conditionCollection->add(new IsTurnoverCreatedConditionPlugin(), 'MyWorld/IsTurnoverCreated');
            $conditionCollection->add(new IsTurnoverCancelledConditionPlugin(), 'MyWorld/IsTurnoverCancelled');

            // ----- MyWorld Payments
            $conditionCollection->add(new IsMyWorldPaymentInitiated(), 'MyWorld/IsMyWorldPaymentInitiated');
            $conditionCollection->add(new IsMyWorldPaymentProcessed(), 'MyWorld/IsMyWorldPaymentProcessed');
            $conditionCollection->add(new IsMyWorldPaymentUsed(), 'MyWorld/IsMyWorldPaymentUsed');

            // ----- Refund
            $conditionCollection->add(new IsFailedCondition(), 'Refund/IsFailed');
            $conditionCollection->add(new IsPendingCondition(), 'Refund/IsPending');
            $conditionCollection->add(new IsRefundedCondition(), 'Refund/IsRefunded');
            $conditionCollection->add(new IsAuthorizedToRefundCondition(), 'Refund/IsAuthorizedToRefund');
            $conditionCollection->add(new IsPossibleToRefundAfterClosingCondition(), 'Refund/IsPossibleToRefundAfterClosing');

            return $conditionCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container)
    {
        return [
            new AvailabilityHandlerPlugin(),
            new ProductBundleAvailabilityHandlerPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[]
     */
    protected function getOmsOrderMailExpanderPlugins(Container $container)
    {
        return [
            new ShipmentOrderMailExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[]
     */
    protected function getOmsManualEventGrouperPlugins(Container $container)
    {
        return [
            new ShipmentManualEventGrouperPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container): Container
    {
        $container->set(static::FACADE_REFUND, function (Container $container) {
            return $container->getLocator()->refund()->facade();
        });

        return $container;
    }
}
