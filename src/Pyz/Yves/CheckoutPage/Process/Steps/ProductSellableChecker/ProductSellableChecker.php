<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductSellableChecker implements ProductSellableCheckerInterface
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
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator
    ) {
        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isQuoteValid
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer, bool $isQuoteValid): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $attributes = $itemTransfer->getConcreteAttributes();
            $countryIso2Code = $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();
            $sellableAttributeKey = sprintf(static::KEY_SELLABLE_ATTRIBUTE_PATTERN, strtolower($countryIso2Code));
            $isSellable = $attributes[$sellableAttributeKey] ?? false;

            if (!$isSellable) {
                $isQuoteValid = false;
                break;
            }
        }

        if (!$isQuoteValid) {
            $this->flashMessenger->addErrorMessage(
                $this->translator->trans(static::KEY_MESSAGE_IS_NOT_SELLABLE)
            );
        }

        return $isQuoteValid;
    }
}
