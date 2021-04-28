<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep as SprykerSummaryStep;
use Symfony\Component\HttpFoundation\Request;

class SummaryStep extends SprykerSummaryStep
{
    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PreConditionChecker
     */
    private $preConditionChecker;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PostConditionChecker
     */
    private $postConditionChecker;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface $preConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     */
    public function __construct(
        CheckoutPageToProductBundleClientInterface $productBundleClient,
        CheckoutPageToShipmentServiceInterface $shipmentService,
        CheckoutPageConfig $checkoutPageConfig,
        $stepRoute,
        $escapeRoute,
        CheckoutPageToCheckoutClientInterface $checkoutClient,
        PreConditionCheckerInterface $preConditionChecker,
        PostConditionCheckerInterface $postConditionChecker
    ) {
        parent::__construct($productBundleClient, $shipmentService, $checkoutPageConfig, $stepRoute, $escapeRoute, $checkoutClient);
        $this->preConditionChecker = $preConditionChecker;
        $this->postConditionChecker = $postConditionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        $return = parent::preCondition($quoteTransfer);

        if ($return) {
            $return = $this->preConditionChecker->check($quoteTransfer);
            $this->escapeRoute = CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT;
        }

        return $return;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $this->markCheckoutConfirmed($request, $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        $viewData = parent::getTemplateVariables($quoteTransfer);
        $viewData['showCashbackPoints'] = $this->hasBenefitDealsApplied($quoteTransfer);

        return $viewData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function markCheckoutConfirmed(Request $request, QuoteTransfer $quoteTransfer)
    {
        if ($request->isMethod('POST')) {
            $quoteTransfer->setCheckoutConfirmed(true);

            if (isset($request->get('summaryForm')[QuoteTransfer::SMS_CODE])) {
                $isValid = $this->postConditionChecker->check($quoteTransfer);
                $quoteTransfer->setCheckoutConfirmed($isValid);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasBenefitDealsApplied(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() || $itemTransfer->getUseShoppingPoints()) {
                return true;
            }
        }

        return false;
    }
}
