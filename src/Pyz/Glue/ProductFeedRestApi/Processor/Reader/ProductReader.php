<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Reader;

use Pyz\Client\ProductFeed\ProductFeedClientInterface;

class ProductReader implements ProductReaderInterface
{
    protected const BRAND_ELITE_CLUB = 'EliteClub';
    protected const BRAND_ONE_SENSE = 'OneSense';
    protected const BRAND_LYCONET = 'Lyconet';

    /**
     * @var \Pyz\Client\ProductFeed\ProductFeedClientInterface
     */
    protected $productFeedClient;

    /**
     * @var \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander\ReaderExpanderInterface[]
     */
    protected $readerExpanderPlugins;

    /**
     * @param \Pyz\Client\ProductFeed\ProductFeedClientInterface $productFeedClient
     * @param array $readerExpanderPlugins
     */
    public function __construct(
        ProductFeedClientInterface $productFeedClient,
        array $readerExpanderPlugins
    ) {
        $this->productFeedClient = $productFeedClient;
        $this->readerExpanderPlugins = $readerExpanderPlugins;
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findRegularProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', array_merge([
                'benefit-store' => false,
                'shopping-point-store' => false,
            ], $requestParameters));

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findFeaturedBenefitVoucherProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'benefit-store' => true,
                'featured-product' => true,
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findShoppingPointProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'shopping-point-store' => true,
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findEliteClubProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'brand' => [
                    static::BRAND_ELITE_CLUB,
                ],
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findOneSenseProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'brand' => [
                    static::BRAND_ONE_SENSE,
                ],
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findLyconetProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'brand' => [
                    static::BRAND_LYCONET,
                ],
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findFeaturedProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'featured-product' => true,
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findOnlyEliteClubDealProducts(array $requestParameters): array
    {
        $catalogSearchResult = $this
            ->productFeedClient
            ->catalogSearch('', [
                'only-ec-deals' => true,
            ]);

        return $this->extendResults($catalogSearchResult);
    }

    /**
     * @param array $catalogSearchResult
     *
     * @return array
     */
    protected function extendResults(array $catalogSearchResult): array
    {
        foreach ($this->readerExpanderPlugins as $readerExpanderPlugin) {
            $catalogSearchResult = $readerExpanderPlugin->expand($catalogSearchResult);
        }

        return $catalogSearchResult;
    }

    /**
     * @param array $catalogSearchProducts
     *
     * @return array
     */
    protected function getProductAbstractIds(array $catalogSearchProducts): array
    {
        $productAbstractIds = [];
        foreach ($catalogSearchProducts as $singleProductResult) {
            $productAbstractIds[] = $singleProductResult['id_product_abstract'];
        }

        return $productAbstractIds;
    }
}
