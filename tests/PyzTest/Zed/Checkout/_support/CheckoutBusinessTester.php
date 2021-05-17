<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Checkout;

use Codeception\Actor;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Price\PriceConfig;

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
class CheckoutBusinessTester extends Actor
{
    use _generated\CheckoutBusinessTesterActions;

    private const MY_WORLD_CUSTOMER_ID = '123.456.789';

    /**
     * @param array $quoteDataOverride
     * @param array $itemsOverride
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuoteTransfer(array $quoteDataOverride = [], array $itemsOverride = []): QuoteTransfer
    {
        $quoteDataOverride = array_merge(
            [
                QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_GROSS,
                QuoteTransfer::ORDER_REFERENCE => 'order-reference',
                QuoteTransfer::CUSTOMER_REFERENCE => 'TEST_001',
            ],
            $quoteDataOverride
        );

        $quoteBuilder = (new QuoteBuilder($quoteDataOverride))
            ->withItem(
                (new ItemBuilder([
                    ItemTransfer::ID_PRODUCT_ABSTRACT => 1,
                    ItemTransfer::UNIT_GROSS_PRICE => 500,
                    ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 500,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONCRETE_ATTRIBUTES => [
                        'sellable_de' => true,
                    ],
                ]))->withShipment(
                    (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                        ->withShippingAddress((new AddressBuilder([AddressTransfer::ISO2_CODE => 'DE'])))
                        ->withMethod()
                )
            );

        foreach ($itemsOverride as $itemOverride) {
            $quoteBuilder->withAnotherItem(
                (new ItemBuilder($itemOverride))
                    ->withShipment(
                        (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                            ->withShippingAddress((new AddressBuilder([AddressTransfer::ISO2_CODE => 'DE'])))
                            ->withMethod()
                    )
            );
        }

        $quoteSeedData = $quoteBuilder
            ->withBillingAddress()
            ->withCustomer([
                CustomerTransfer::MY_WORLD_CUSTOMER_ID => self::MY_WORLD_CUSTOMER_ID,
            ])
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress(
                (new AddressBuilder())->withCountry([
                    CountryTransfer::ISO2_CODE => 'DE',
                ])
            )
            ->build()->toArray();

        return $this->havePersistentQuote($quoteSeedData);
    }
}
