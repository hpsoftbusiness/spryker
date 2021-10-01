<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount;

use Pyz\Zed\Discount\Communication\Plugin\Calculator\BenefitPriceDiscountCalculator;
use Pyz\Zed\Discount\Communication\Plugin\Collector\ShoppingPointsItemDiscountCollectorPlugin;
use Pyz\Zed\Discount\Communication\Plugin\InternalDiscount\ShoppingPointsDiscountPlugin;
use Pyz\Zed\Discount\Communication\Plugin\InternalDiscountCollectorStrategyPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Discount\DiscountableItemWithDealsFilterPlugin;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Discount\HideBenefitPriceCalculatorDiscountFormDataProviderExpanderPlugin;
use Spryker\Zed\CustomerGroupDiscountConnector\Communication\Plugin\DecisionRule\CustomerGroupDecisionRulePlugin;
use Spryker\Zed\Discount\DiscountDependencyProvider as SprykerDiscountDependencyProvider;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionCalculationFormExpanderPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionCleanerPostUpdatePlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionCollectorStrategyPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionConfigurationExpanderPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionFilterApplicableItemsPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionFilterCollectedItemsPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionPostCreatePlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionPostUpdatePlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\DiscountPromotionViewBlockProviderPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount\PromotionCollectedDiscountGroupingStrategyPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\GiftCardDiscountableItemFilterPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscountConnector\Communication\Plugin\Collector\ProductAttributeCollectorPlugin;
use Spryker\Zed\ProductDiscountConnector\Communication\Plugin\DecisionRule\ProductAttributeDecisionRulePlugin;
use Spryker\Zed\ProductLabelDiscountConnector\Communication\Plugin\Collector\ProductLabelCollectorPlugin;
use Spryker\Zed\ProductLabelDiscountConnector\Communication\Plugin\DecisionRule\ProductLabelDecisionRulePlugin;
use Spryker\Zed\SalesQuantity\Communication\Plugin\DiscountExtension\NonSplittableDiscountableItemTransformerStrategyPlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule\ShipmentCarrierDecisionRulePlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule\ShipmentMethodDecisionRulePlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DecisionRule\ShipmentPriceDecisionRulePlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DiscountCollector\ItemByShipmentCarrierPlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DiscountCollector\ItemByShipmentMethodPlugin;
use Spryker\Zed\ShipmentDiscountConnector\Communication\Plugin\DiscountCollector\ItemByShipmentPricePlugin;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

class DiscountDependencyProvider extends SprykerDiscountDependencyProvider
{
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    public const PLUGIN_CALCULATOR_BENEFIT_PRICE = 'PLUGIN_CALCULATOR_BENEFIT_PRICE';
    public const PLUGIN_INTERNAL_DISCOUNT = 'PLUGIN_INTERNAL_DISCOUNT';
    public const PLUGIN_INTERNAL_DISCOUNT_COLLECTOR = 'PLUGIN_INTERNAL_DISCOUNT_COLLECTOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addInternalDiscountPlugins($container);
        $this->addInternalDiscountCollectorPlugins($container);

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

        $this->addGlossaryFacade($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getAvailableCalculatorPlugins(): array
    {
        return array_merge(
            parent::getAvailableCalculatorPlugins(),
            [
                self::PLUGIN_CALCULATOR_BENEFIT_PRICE => new BenefitPriceDiscountCalculator(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    protected function getDecisionRulePlugins()
    {
        return array_merge(parent::getDecisionRulePlugins(), [
            new ShipmentCarrierDecisionRulePlugin(),
            new ShipmentMethodDecisionRulePlugin(),
            new ShipmentPriceDecisionRulePlugin(),
            new CustomerGroupDecisionRulePlugin(),
            new ProductLabelDecisionRulePlugin(),
            new ProductAttributeDecisionRulePlugin(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected function getCollectorPlugins()
    {
        return array_merge(parent::getCollectorPlugins(), [
            new ProductLabelCollectorPlugin(),
            new ItemByShipmentCarrierPlugin(),
            new ItemByShipmentMethodPlugin(),
            new ItemByShipmentPricePlugin(),
            new ProductAttributeCollectorPlugin(),
        ]);
    }

    /**
     * @return array
     */
    protected function getDiscountableItemFilterPlugins()
    {
        return [
            new DiscountPromotionFilterCollectedItemsPlugin(),
            new GiftCardDiscountableItemFilterPlugin(), #GiftCardFeature
            new DiscountableItemWithDealsFilterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[]
     */
    protected function getDiscountableItemTransformerStrategyPlugins(): array
    {
        return [
            new NonSplittableDiscountableItemTransformerStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface[]
     */
    protected function getCollectorStrategyPlugins()
    {
        return [
            new DiscountPromotionCollectorStrategyPlugin(),
            new InternalDiscountCollectorStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface[]
     */
    protected function getDiscountPostCreatePlugins()
    {
        return [
            new DiscountPromotionPostCreatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface[]
     */
    protected function getDiscountPostUpdatePlugins()
    {
        return [
            new DiscountPromotionPostUpdatePlugin(),
            new DiscountPromotionCleanerPostUpdatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountConfigurationExpanderPluginInterface[]
     */
    protected function getDiscountConfigurationExpanderPlugins()
    {
        return [
            new DiscountPromotionConfigurationExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface[]
     */
    protected function getDiscountFormExpanderPlugins()
    {
        return [
            new DiscountPromotionCalculationFormExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormDataProviderExpanderPluginInterface[]
     */
    protected function getDiscountFormDataProviderExpanderPlugins()
    {
        return [
            new HideBenefitPriceCalculatorDiscountFormDataProviderExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountViewBlockProviderPluginInterface[]
     */
    protected function getDiscountViewTemplateProviderPlugins()
    {
        return [
            new DiscountPromotionViewBlockProviderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface[]
     */
    protected function getDiscountApplicableFilterPlugins()
    {
        return [
           new DiscountPromotionFilterApplicableItemsPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin()
    {
        return new StoreRelationToggleFormTypePlugin();
    }

    /**
     * @return \Spryker\Zed\DiscountExtension\Dependency\Plugin\CollectedDiscountGroupingStrategyPluginInterface[]
     */
    protected function getCollectedDiscountGroupingPlugins(): array
    {
        return [
            new PromotionCollectedDiscountGroupingStrategyPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addInternalDiscountPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_INTERNAL_DISCOUNT, function () {
            return [
                new ShoppingPointsDiscountPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addInternalDiscountCollectorPlugins(Container $container): void
    {
        $container->set(self::PLUGIN_INTERNAL_DISCOUNT_COLLECTOR, function () {
            return [
                new ShoppingPointsItemDiscountCollectorPlugin(),
            ];
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addGlossaryFacade(Container $container): void
    {
        $container->set(self::FACADE_GLOSSARY, static function (Container $container) {
            return $container->getLocator()->glossary()->facade();
        });
    }
}
