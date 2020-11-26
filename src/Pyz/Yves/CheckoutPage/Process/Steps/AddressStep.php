<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep as SprykerShopAddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressStep extends SprykerShopAddressStep
{
    protected const KEY_SELLABLE_ATTRIBUTE_PATTERN = 'sellable_%s';

    protected const KEY_MESSAGE_IS_NOT_SELLABLE = 'checkout.step.address.is_sellable';

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface $stepExecutor
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface[] $checkoutAddressStepEnterPreCheckPlugins
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepExecutorInterface $stepExecutor,
        PostConditionCheckerInterface $postConditionChecker,
        CheckoutPageConfig $checkoutPageConfig,
        $stepRoute,
        $escapeRoute,
        array $checkoutAddressStepEnterPreCheckPlugins,
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator
    ) {
        parent::__construct(
            $calculationClient,
            $stepExecutor,
            $postConditionChecker,
            $checkoutPageConfig,
            $stepRoute,
            $escapeRoute,
            $checkoutAddressStepEnterPreCheckPlugins
        );

        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if (!$this->executeCheckoutAddressStepEnterPreCheckPlugins($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteTransfer = $this->stepExecutor->execute($request, $quoteTransfer);

        $storeName = $quoteTransfer->getStore()->getName();

        foreach ($quoteTransfer->getItems() as $idx => $itemTransfer) {
            $hiddenAttributes = $itemTransfer->getHiddenConcreteAttributes();
            $key = sprintf(static::KEY_SELLABLE_ATTRIBUTE_PATTERN, $storeName);
            $isSellable = (bool)$hiddenAttributes[$key];

            if (!$isSellable || $itemTransfer->getSku() === '9120050580220-0002') {
                unset($quoteTransfer->getItems()[$idx]);
                $this->flashMessenger->addErrorMessage(
                    $this->translator->trans(static::KEY_MESSAGE_IS_NOT_SELLABLE)
                );
            }
        }

        return $this->calculationClient->recalculate($quoteTransfer);
    }
}
