<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Pyz\Shared\ProductApi\ProductApiConstants;
use Pyz\Zed\ProductApi\Business\Exception\UnsupportedResourceException;
use Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface;
use Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;
use Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface;
use Pyz\Zed\ProductApi\ProductApiConfig;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

/**
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
class ProductApi implements ProductApiInterface
{
    /**
     * @var \Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @var \Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @param \Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     * @param \Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface $transferMapper
     * @param \Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface $productFacade
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface $productCategoryFacade
     */
    public function __construct(
        ProductApiToApiInterface $apiQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
        TransferMapperInterface $transferMapper,
        ProductApiToProductInterface $productFacade,
        LocaleFacadeInterface $localeFacade,
        ProductCategoryFacadeInterface $productCategoryFacade
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->transferMapper = $transferMapper;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->productCategoryFacade = $productCategoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer
    {
        $query = $this->buildQuery($apiRequestTransfer);
        $localeTransfer = $this->localeFacade->getLocale($this->getLanguage($apiRequestTransfer));

        $responseApiTransfer = $this->transferMapper->toTransferCollection(
            $query->find()->toArray(),
            $localeTransfer,
            $apiRequestTransfer->getResource()
        );

        $productsApiTransfers = new ArrayObject();
        /** @var \Generated\Shared\Transfer\ProductApiTransfer $productApiTransfer */
        foreach ($responseApiTransfer->getProducts() as $k => $productApiTransfer) {
            $productsApiTransfers[] = $this->get($productApiTransfer->getProductId(), $localeTransfer);
        }
        $responseApiTransfer->setProducts($productsApiTransfers);

        return $this->apiQueryContainer->createApiItem($responseApiTransfer->toArray(true, true));
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    protected function get(
        int $idProductAbstract,
        LocaleTransfer $localeTransfer
    ): ProductApiTransfer {
        $productTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);
        $productUrl = $this->productFacade->getProductUrl($productTransfer);
        $productCategoryTransferCollection = $this->productCategoryFacade
            ->getCategoryTransferCollectionByIdProductAbstract(
                $productTransfer->getIdProductAbstract(),
                $localeTransfer
            );

        return $this->transferMapper->toTransfer(
            $productTransfer,
            $productUrl,
            $localeTransfer,
            $productCategoryTransferCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Pyz\Zed\ProductApi\Business\Exception\UnsupportedResourceException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer): SpyProductAbstractQuery
    {
        switch ($apiRequestTransfer->getResource()) {
            case ProductApiConfig::RESOURCE_PRODUCTS:
                return $this->queryContainer->queryRegularProducts();
            case ProductApiConfig::RESOURCE_BVDEALS:
                return $this->queryContainer->queryBvDeals();
            case ProductApiConfig::RESOURCE_SPDEALS:
                return $this->queryContainer->querySpDeals();
            case ProductApiConfig::RESOURCE_ELITE_CLUB:
                return $this->queryContainer->queryEliteClub();
            case ProductApiConfig::RESOURCE_ONE_SENSE:
                return $this->queryContainer->queryOneSense();
            case ProductApiConfig::RESOURCE_LYCONET:
                return $this->queryContainer->queryLyconet();
            case ProductApiConfig::RESOURCE_FEATURED_PRODUCTS:
                return $this->queryContainer->queryFeaturedProducts();
            case ProductApiConfig::RESOURCE_ELITE_CLUB_EC_DEAL_ONLY:
                return $this->queryContainer->queryEliteClubEcDealOnly();
            default:
                throw new UnsupportedResourceException($apiRequestTransfer->getResource());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return string|null
     */
    protected function getLanguage(ApiRequestTransfer $apiRequestTransfer): ?string
    {
        $headerData = array_change_key_case($apiRequestTransfer->getHeaderData(), CASE_UPPER);

        return $headerData[ProductApiConstants::X_SPRYKER_LANGUAGE][0] ?? null;
    }
}
