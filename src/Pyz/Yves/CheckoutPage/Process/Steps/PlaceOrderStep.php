<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep as SprykerShopPlaceOrderStep;
use Symfony\Component\HttpFoundation\Request;

class PlaceOrderStep extends SprykerShopPlaceOrderStep
{
    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface
     */
    protected $productSellableChecker;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    private $preConditionChecker;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param string $currentLocale
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface $productSellableChecker
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface|array $preConditionChecker
     * @param array $errorCodeToRouteMatching
     */
    public function __construct(
        CheckoutPageToCheckoutClientInterface $checkoutClient,
        FlashMessengerInterface $flashMessenger,
        string $currentLocale,
        CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient,
        $stepRoute,
        $escapeRoute,
        ProductSellableCheckerInterface $productSellableChecker,
        PreConditionCheckerInterface $preConditionChecker,
        $errorCodeToRouteMatching = []
    ) {
        parent::__construct(
            $checkoutClient,
            $flashMessenger,
            $currentLocale,
            $glossaryStorageClient,
            $stepRoute,
            $escapeRoute,
            $errorCodeToRouteMatching
        );

        $this->productSellableChecker = $productSellableChecker;
        $this->preConditionChecker = $preConditionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        if ($this->isCartEmpty($quoteTransfer)) {
            return false;
        }

        if (!$quoteTransfer->getCheckoutConfirmed()) {
            $this->escapeRoute = CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY;

            return false;
        }

        if (!$this->preConditionChecker->check($quoteTransfer)) {
            $this->escapeRoute = CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY;

            return false;
        }

        if (!$this->productSellableChecker->check($quoteTransfer, true)) {
            $this->escapeRoute = CartPageRouteProviderPlugin::ROUTE_NAME_CART;

            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsExternalRedirect()) {
            $this->externalRedirectUrl = $checkoutResponseTransfer->getRedirectUrl();
        }

        if ($checkoutResponseTransfer->getSaveOrder() !== null) {
            $quoteTransfer->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
        }

        $this->setCheckoutErrorMessages($checkoutResponseTransfer);
        $this->checkoutResponseTransfer = $checkoutResponseTransfer;

        $quoteTransfer->getPayment()->setAdyenRedirect($checkoutResponseTransfer->getAdyenRedirect());

        return $quoteTransfer;
    }
}
