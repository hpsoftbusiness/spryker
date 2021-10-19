<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\ProductBenefitApiTransfer;
use Generated\Shared\Transfer\ProductBvDealApiTransfer;
use Generated\Shared\Transfer\ProductPriceApiTransfer;
use Generated\Shared\Transfer\ProductSpDealApiTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Pyz\Client\Money\MoneyClientInterface;
use Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig;
use Spryker\Shared\Kernel\Store;

class ProductFeedMapper
{
    protected const KEY_PRODUCTS = 'products';

    /**
     * @var \Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Pyz\Client\Money\MoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig $config
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Pyz\Client\Money\MoneyClientInterface $moneyClient
     */
    public function __construct(
        ProductFeedRestApiConfig $config,
        Store $store,
        MoneyClientInterface $moneyClient
    ) {
        $this->config = $config;
        $this->store = $store;
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param array $catalogSearchResult
     *
     * @return \Generated\Shared\Transfer\ProductsResponseApiTransfer
     */
    public function mapSearchResultToApiResponse(array $catalogSearchResult): ProductsResponseApiTransfer
    {
        $catalogSearchProducts = $catalogSearchResult[self::KEY_PRODUCTS];
        $products = $this->mapProducts($catalogSearchProducts);

        return (new ProductsResponseApiTransfer())
            ->setLanguage(
                $this->store->getCurrentLocale()
            )
            ->setProducts($products);
    }

    /**
     * @param array $catalogSearchProducts
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer[]|\ArrayObject
     */
    protected function mapProducts(array $catalogSearchProducts): ArrayObject
    {
        $products = new ArrayObject();
        foreach ($catalogSearchProducts as $singleProductResult) {
            $products[] = $this->mapSingleProduct($singleProductResult);
        }

        return $products;
    }

    /**
     * @param array $singleProductResult
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    protected function mapSingleProduct(array $singleProductResult): ProductApiTransfer
    {
        $product = new ProductApiTransfer();
        $product
            ->setProductId($singleProductResult['id_product_abstract'])
            ->setProductName($singleProductResult['abstract_name'] ?? '')
            ->setDescription($singleProductResult['description'] ?? '')
            ->setCategory($singleProductResult['category'] ?? '')
            ->setImageUrl(
                $this->getFirstLargeImage($singleProductResult)
            )
            ->setProductUrl(
                $this->config->getYvesHost() . $singleProductResult['url']
            )
            ->setMarketerOnly(
                $this->getMarketerOnlyFlag($singleProductResult)
            )
            ->setCbwPrivateOnly(
                $this->getCbwPrivateOnlyFlag($singleProductResult)
            )
            ->setBenefit(
                (new ProductBenefitApiTransfer())
                    ->setCashbackAmount(
                        $this->formatNumber(
                            $this->getCashbackAmount($singleProductResult)
                        )
                    )
                    ->setShoppingPointsAmount(
                        $this->formatNumber(
                            $this->getShoppingPoints($singleProductResult)
                        )
                    )
            )
            ->setPrice(
                (new ProductPriceApiTransfer())
                    ->setAmount(
                        $this->formatAmount(
                            $this->getPriceDefault($singleProductResult)
                        )
                    )
                    ->setCurrency($this->store->getCurrencyIsoCode())
            )
            ->setOriginalGrossPrice(
                (new ProductPriceApiTransfer())
                    ->setAmount(
                        $this->formatAmount(
                            $this->getPriceOriginal($singleProductResult)
                        )
                    )
                    ->setCurrency($this->store->getCurrencyIsoCode())
            )
            ->setBvDeal(
                (new ProductBvDealApiTransfer())
                    ->setBvAmount(
                        $this->formatAmount(
                            $this->getBenefitAmount($singleProductResult)
                        )
                    )
                    ->setBvItemPrice(
                        $this->formatAmount(
                            $this->getPriceBenefit($singleProductResult)
                        )
                    )
            )
            ->setSpDeal(
                (new ProductSpDealApiTransfer())
                    ->setSpAmount(
                        $this->formatNumber(
                            $this->getShoppingPointsAmount($singleProductResult)
                        )
                    )
                    ->setSpItemPrice(
                        $this->formatAmount(
                            $this->getPriceBenefit($singleProductResult)
                        )
                    )
            );

        return $product;
    }

    /**
     * @param array $singleProductResult
     *
     * @return string
     */
    protected function getFirstLargeImage(array $singleProductResult): string
    {
        foreach ($singleProductResult['images'] as $image) {
            if (!empty($image['external_url_large'])) {
                return $image['external_url_large'];
            }
        }

        return "";
    }

    /**
     * @param array $singleProductResult
     *
     * @return bool
     */
    protected function getMarketerOnlyFlag(array $singleProductResult): bool
    {
        return $this->getAttributeBoolValue($singleProductResult, 'marketer_only');
    }

    /**
     * @param array $singleProductResult
     *
     * @return bool
     */
    protected function getCbwPrivateOnlyFlag(array $singleProductResult): bool
    {
        return $this->getAttributeBoolValue($singleProductResult, 'cbw_private_only');
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getCashbackAmount(array $singleProductResult): ?int
    {
        return $this->getAttributeIntValue($singleProductResult, 'cashback_amount');
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getShoppingPoints(array $singleProductResult): ?int
    {
        return $this->getAttributeIntValue($singleProductResult, 'shopping_points');
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getShoppingPointsAmount(array $singleProductResult): ?int
    {
        $shoppingPointsAmountKey = $this->config->getShoppingPointsAmountKey();

        return $this->getAttributeIntValue($singleProductResult, $shoppingPointsAmountKey);
    }

    /**
     * @param array $singleProductResult
     * @param string $flagKey
     *
     * @return bool
     */
    protected function getAttributeBoolValue(array $singleProductResult, string $flagKey): bool
    {
        $flagValue = $singleProductResult['attributes'][$flagKey] ?? false;

        return $flagValue === true || $flagValue === '1';
    }

    /**
     * @param array $singleProductResult
     * @param string $integerKey
     *
     * @return int|null
     */
    protected function getAttributeIntValue(array $singleProductResult, string $integerKey): ?int
    {
        $value = $singleProductResult['attributes'][$integerKey] ?? null;
        if ($value === null) {
            return null;
        }

        return (int)$value;
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getPriceOriginal(array $singleProductResult): ?int
    {
        return $singleProductResult['prices']['ORIGINAL'] ?? null;
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getPriceDefault(array $singleProductResult): ?int
    {
        return $singleProductResult['prices']['DEFAULT'] ?? null;
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getPriceBenefit(array $singleProductResult): ?int
    {
        return $singleProductResult['prices']['BENEFIT'] ?? null;
    }

    /**
     * @param array $singleProductResult
     *
     * @return int|null
     */
    protected function getBenefitAmount(array $singleProductResult): ?int
    {
        $benefitPrice = $this->getPriceBenefit($singleProductResult);
        if ($benefitPrice === null) {
            return null;
        }
        $benefitAmount = $this->getPriceDefault($singleProductResult) - $benefitPrice;
        if ($benefitAmount === 0) {
            return null;
        }

        return $benefitAmount;
    }

    /**
     * @param int|null $amount
     *
     * @return string|null
     */
    protected function formatAmount(?int $amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        $moneyTransfer = $this->moneyClient->fromInteger($amount, $this->store->getCurrencyIsoCode());

        $formattedAmount = $this->moneyClient->formatWithoutSymbol($moneyTransfer);

        return $this->removeSpace($formattedAmount);
    }

    /**
     * @param string $formattedAmount
     *
     * @return string
     */
    protected function removeSpace(string $formattedAmount): string
    {
        return str_replace(' ', '', $formattedAmount);
    }

    /**
     * @param int|null $amount
     *
     * @return string|null
     */
    protected function formatNumber(?int $amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        return number_format((float)$amount, 2, '.', '');
    }
}
