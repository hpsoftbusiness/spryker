<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep as SprykerShopShipmentStep;

class ShipmentStep extends SprykerShopShipmentStep
{
    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface
     */
    protected $productSellableChecker;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $shipmentPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface $giftCardItemsChecker
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface[] $checkoutShipmentStepEnterPreCheckPlugins
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface $productSellableChecker
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        PostConditionCheckerInterface $postConditionChecker,
        GiftCardItemsCheckerInterface $giftCardItemsChecker,
        $stepRoute,
        $escapeRoute,
        array $checkoutShipmentStepEnterPreCheckPlugins,
        ProductSellableCheckerInterface $productSellableChecker
    ) {
        parent::__construct(
            $calculationClient,
            $shipmentPlugins,
            $postConditionChecker,
            $giftCardItemsChecker,
            $stepRoute,
            $escapeRoute,
            $checkoutShipmentStepEnterPreCheckPlugins
        );

        $this->productSellableChecker = $productSellableChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        $isQuoteValid = parent::preCondition($quoteTransfer);
        $isQuoteValid = $this->productSellableChecker->check($quoteTransfer, $isQuoteValid);

        if (!$isQuoteValid) {
            $this->escapeRoute = CartPageRouteProviderPlugin::ROUTE_NAME_CART;
        }

        return $isQuoteValid;
    }
}
