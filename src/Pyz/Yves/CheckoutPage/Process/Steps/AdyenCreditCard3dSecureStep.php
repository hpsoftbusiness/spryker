<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Adyen\AdyenConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class AdyenCreditCard3dSecureStep extends AbstractBaseStep
{
    /**
     * @var \Pyz\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \Pyz\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     */
    public function __construct(
        $stepRoute,
        $escapeRoute,
        CheckoutPageConfig $checkoutPageConfig
    ) {
        parent::__construct($stepRoute, $escapeRoute);
        $this->checkoutPageConfig = $checkoutPageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        if ($this->is3dSecureRequired($quoteTransfer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        return [
            'action' => $quoteTransfer->getPayment()->getAdyenRedirect()->getAction(),
            'fields' => $quoteTransfer->getPayment()->getAdyenRedirect()->getFields(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function is3dSecureRequired(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getPayment()
            && $quoteTransfer->getPayment()->getPaymentSelection() === AdyenConfig::ADYEN_CREDIT_CARD
            && $this->checkoutPageConfig->isAdyenCreditCard3dSecureEnabled()
            && $quoteTransfer->getPayment()->getAdyenRedirect();
    }
}
