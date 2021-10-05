<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Processor\Catalog;

use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Pyz\Glue\ProductFeedRestApi\Processor\Mapper\ProductFeedMapper;
use Pyz\Glue\ProductFeedRestApi\Processor\Reader\ProductReaderInterface;
use Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CatalogSearchReader
{
    /**
     * @var \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Pyz\Glue\ProductFeedRestApi\Processor\Mapper\ProductFeedMapper
     */
    protected $productFeedMapper;

    /**
     * @param \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ProductReaderInterface $productReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Pyz\Glue\ProductFeedRestApi\Processor\Mapper\ProductFeedMapper $productFeedMapper
     */
    public function __construct(
        ProductReaderInterface $productReader,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductFeedMapper $productFeedMapper
    ) {
        $this->productReader = $productReader;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productFeedMapper = $productFeedMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findRegularProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findRegularProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findBenefitVoucherProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findBenefitVoucherProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findShoppingPointProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findShoppingPointProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findEliteClubProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findEliteClubProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findOneSenseProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findOneSenseProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findLyconetProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findLyconetProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findFeaturedProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findFeaturedProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findOnlyEliteClubDealProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchResult = $this->productReader->findOnlyEliteClubDealProducts(
            $this->getPageRequestParameters($restRequest)
        );

        return $this->buildCatalogSearchResponse($restRequest, $searchResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function getPageRequestParameters(RestRequestInterface $restRequest): array
    {
        return [
            'page' => $restRequest->getHttpRequest()->get('page', 1),
        ];
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $searchResult
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildCatalogSearchResponse(
        RestRequestInterface $restRequest,
        array $searchResult
    ): RestResponseInterface {
        $restProductFeedTransfer = $this
            ->productFeedMapper
            ->mapSearchResultToApiResponse($searchResult);

        $this->updateTitleInResponse($restRequest, $restProductFeedTransfer);

        $restResource = $this
            ->restResourceBuilder
            ->createRestResource(
                ProductFeedRestApiConfig::RESOURCE_PRODUCT_FEED,
                null,
                $restProductFeedTransfer
            );

        $response = $this
            ->restResourceBuilder
            ->createRestResponse(
                $restProductFeedTransfer
                    ->getProducts()
                    ->count()
            );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ProductsResponseApiTransfer $restProductFeedTransfer
     *
     * @return void
     */
    protected function updateTitleInResponse(
        RestRequestInterface $restRequest,
        ProductsResponseApiTransfer $restProductFeedTransfer
    ): void {
        $resourceType = $restRequest->getResource()->getType();
        $title = ProductFeedRestApiConfig::RESOURCE_TYPE_TO_TITLE_MAP[$resourceType] ?? '';
        $restProductFeedTransfer->setTitle($title);
    }
}
