<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractFullUrlExpanderPlugin;
use Spryker\Shared\Application\ApplicationConstants;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractFullUrlExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractFullUrlExpanderPluginTest extends Unit
{
    private const BASE_URL = 'https://yves.de.myworld.local';
    private const PRODUCT_URL = '/de/test-product';
    private const FULL_URL = 'https://yves.de.myworld.local/de/test-product';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractFullUrlExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractFullUrlExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param string|null $baseUrl
     * @param string|null $productUrl
     * @param string|null $fullUrl
     *
     * @return void
     */
    public function testProductAbstractFullUrlMapped(?string $baseUrl, ?string $productUrl, ?string $fullUrl): void
    {
        $this->tester->setConfig(ApplicationConstants::BASE_URL_YVES, $baseUrl);
        $restCatalogSearchAbstractProductTransfer = $this->createRestCatalogSearchAbstractProductTransfer($productUrl);
        $this->sut->expand([], $restCatalogSearchAbstractProductTransfer);
        self::assertEquals($fullUrl, $restCatalogSearchAbstractProductTransfer->getFullUrl());
    }

    /**
     * @return array
     */
    public function productDataProvider(): array
    {
        return [
            'fullUrlBuildAndMapped' => [
                self::BASE_URL,
                self::PRODUCT_URL,
                self::FULL_URL,
            ],
            'fullUrlNotCreatedIfBaseUrlNotSet' => [
                '',
                self::PRODUCT_URL,
                null,
            ],
            'fullUrlNotCreatedIfProductUrlMissing' => [
                self::BASE_URL,
                null,
                null,
            ],
        ];
    }

    /**
     * @param string|null $url
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer
     */
    private function createRestCatalogSearchAbstractProductTransfer(?string $url): RestCatalogSearchAbstractProductsTransfer
    {
        return (new RestCatalogSearchAbstractProductsTransfer())->setUrl($url);
    }
}
