<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceModeConfigurationBuilder;
use Generated\Shared\DataBuilder\RestCatalogSearchAbstractProductsBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Pyz\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;
use Spryker\Shared\Price\PriceConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Processor
 * @group Mapper
 * @group CatalogSearchResourceMapperTest
 * Add your own group annotations below this line
 */
class CatalogSearchResourceMapperTest extends Unit
{
    private const PRICE_TYPE_DEFAULT = 'DEFAULT';
    private const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiProcessorTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    private $sut;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $currencyClientMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->currencyClientMock = $this->mockCurrencyClientBridge();
        $this->sut = $this->createCatalogSearchResourceMapper();
    }

    /**
     * @return void
     */
    public function testEmptyPricesAreFilteredOut(): void
    {
        $restCatalogSearchAttributesTransfer = $this->createRestCatalogSearchAttributesTransfer();
        $catalogSearchAbstractProductTransfer = $this->buildRestCatalogSearchAbstractProductsTransfer([
            self::PRICE_TYPE_DEFAULT => 190,
            self::PRICE_TYPE_ORIGINAL => null,
        ]);
        $restCatalogSearchAttributesTransfer->addAbstractProduct($catalogSearchAbstractProductTransfer);

        $this->currencyClientMock
            ->method('getCurrent')
            ->willReturn(new CurrencyTransfer());

        $this->sut->mapPrices($restCatalogSearchAttributesTransfer, $this->buildPriceModeConfigurationTransfer());

        self::assertCount(1, $catalogSearchAbstractProductTransfer->getPrices());
    }

    /**
     * @return void
     */
    public function testMultipleNonEmptyPricesAreMapped(): void
    {
        $restCatalogSearchAttributesTransfer = $this->createRestCatalogSearchAttributesTransfer();
        $catalogSearchAbstractProductTransfer = $this->buildRestCatalogSearchAbstractProductsTransfer([
            self::PRICE_TYPE_DEFAULT => 190,
            self::PRICE_TYPE_ORIGINAL => 150,
        ]);
        $restCatalogSearchAttributesTransfer->addAbstractProduct($catalogSearchAbstractProductTransfer);

        $this->currencyClientMock
            ->method('getCurrent')
            ->willReturn(new CurrencyTransfer());

        $this->sut->mapPrices($restCatalogSearchAttributesTransfer, $this->buildPriceModeConfigurationTransfer());

        self::assertCount(2, $catalogSearchAbstractProductTransfer->getPrices());
    }

    /**
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    private function createRestCatalogSearchAttributesTransfer(): RestCatalogSearchAttributesTransfer
    {
        return new RestCatalogSearchAttributesTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceModeConfigurationTransfer
     */
    private function buildPriceModeConfigurationTransfer(): PriceModeConfigurationTransfer
    {
        return (new PriceModeConfigurationBuilder([
            PriceModeConfigurationTransfer::CURRENT_PRICE_MODE => PriceConfig::PRICE_MODE_GROSS,
            PriceModeConfigurationTransfer::GROSS_MODE_IDENTIFIER => PriceConfig::PRICE_MODE_GROSS,
            PriceModeConfigurationTransfer::NET_MODE_IDENTIFIER => PriceConfig::PRICE_MODE_NET,
        ]))->build();
    }

    /**
     * @param array $prices
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer
     */
    private function buildRestCatalogSearchAbstractProductsTransfer(array $prices): RestCatalogSearchAbstractProductsTransfer
    {
        return (new RestCatalogSearchAbstractProductsBuilder([
            RestCatalogSearchAbstractProductsTransfer::PRICES => $prices,
        ]))->build();
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCurrencyClientBridge(): CatalogSearchRestApiToCurrencyClientInterface
    {
        return $this->createMock(CatalogSearchRestApiToCurrencyClientInterface::class);
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    private function createCatalogSearchResourceMapper(): CatalogSearchResourceMapperInterface
    {
        return new CatalogSearchResourceMapper($this->currencyClientMock);
    }
}
