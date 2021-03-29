<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper as SprykerCatalogSearchResourceMapper;

class CatalogSearchResourceMapper extends SprykerCatalogSearchResourceMapper
{
    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface[]
     */
    private $catalogSearchAbstractProductExpanderPlugins;

    /**
     * @param \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface $currencyClient
     * @param \Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface[] $catalogSearchAbstractProductExpanderPlugins
     */
    public function __construct(
        CatalogSearchRestApiToCurrencyClientInterface $currencyClient,
        array $catalogSearchAbstractProductExpanderPlugins
    ) {
        parent::__construct($currencyClient);

        $this->catalogSearchAbstractProductExpanderPlugins = $catalogSearchAbstractProductExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceModeConfigurationTransfer $priceModeInformation
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    public function mapPrices(
        RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer,
        PriceModeConfigurationTransfer $priceModeInformation
    ): RestCatalogSearchAttributesTransfer {
        foreach ($restSearchAttributesTransfer->getAbstractProducts() as $product) {
            $prices = [];
            foreach ($product->getPrices() as $priceType => $price) {
                if ($price === null) {
                    continue;
                }

                $priceData = $this
                    ->getPriceTransfer($priceType, $price, $priceModeInformation)
                    ->modifiedToArray(true, true);

                $prices[] = $priceData + [$priceType => $price];
            }
            $product->setPrices($prices);
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restCatalogSearchAttributesTransfer
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    protected function mapSearchResponseProductsToRestCatalogSearchAttributesTransfer(
        RestCatalogSearchAttributesTransfer $restCatalogSearchAttributesTransfer,
        array $restSearchResponse
    ): RestCatalogSearchAttributesTransfer {
        if (!isset($restSearchResponse[static::SEARCH_KEY_PRODUCTS]) || !is_array($restSearchResponse[static::SEARCH_KEY_PRODUCTS])) {
            return $restCatalogSearchAttributesTransfer;
        }

        foreach ($restSearchResponse[static::SEARCH_KEY_PRODUCTS] as $product) {
            $restCatalogSearchAbstractProductTransfer = (new RestCatalogSearchAbstractProductsTransfer())
                ->fromArray($product, true);
            $this->expandRestProductAbstractAttributes($product, $restCatalogSearchAbstractProductTransfer);
            $restCatalogSearchAttributesTransfer->addAbstractProduct($restCatalogSearchAbstractProductTransfer);
        }

        return $restCatalogSearchAttributesTransfer;
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function expandRestProductAbstractAttributes(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        foreach ($this->catalogSearchAbstractProductExpanderPlugins as $expanderPlugin) {
            $expanderPlugin->expand($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
        }
    }
}
