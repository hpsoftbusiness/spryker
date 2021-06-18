<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AdyenApiSplitTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AdyenBusinessTester extends Actor
{
    use _generated\AdyenBusinessTesterActions;

    public const CURRENCY_CODE_EUR = 'EUR';
    public const COMMISSION_REFERENCE = 'commission_reference';
    public const MARKETPLACE_REFERENCE = 'marketplace_reference';

    /**
     * @param array $paymentOverride
     * @param array $paymentCollectionOverride
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuoteTransferWithPayments(
        array $paymentOverride = [],
        array $paymentCollectionOverride = []
    ): QuoteTransfer {
        return (new QuoteBuilder([
            QuoteTransfer::PAYMENTS => array_map(
                function (array $paymentOverride): PaymentTransfer {
                    return (new PaymentBuilder($paymentOverride))->build();
                },
                $paymentCollectionOverride
            ),
            QuoteTransfer::PAYMENT => (new PaymentBuilder($paymentOverride))->withAdyenCreditCard()->build(),
        ]))
            ->withCustomer()
            ->withBillingAddress()
            ->withShippingAddress()
            ->withCurrency([
                CurrencyTransfer::CODE => self::CURRENCY_CODE_EUR,
            ])
            ->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function buildOrderTransfer(array $overrideData): OrderTransfer
    {
        return (new OrderBuilder($overrideData))->build();
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\AdyenApiSplitTransfer[] $splitTransfers
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\AdyenApiSplitTransfer|null
     */
    public function findSplitTransferByType(iterable $splitTransfers, string $type): ?AdyenApiSplitTransfer
    {
        foreach ($splitTransfers as $splitTransfer) {
            if ($splitTransfer->getType() === $type) {
                return $splitTransfer;
            }
        }

        return null;
    }
}
