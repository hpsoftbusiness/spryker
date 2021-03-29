<?php

namespace Pyz\Zed\ProductApi\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Pyz\Shared\ProductApi\ProductApiConstants;
use Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface;
use Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;
use Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface;
use Pyz\Zed\ProductApi\ProductApiConfig;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class ProductApi implements ProductApiInterface
{
    /**
     * @var ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var ProductApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @var ProductApiToProductInterface
     */
    protected $productFacade;

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var ProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @param ProductApiToApiInterface $apiQueryContainer
     * @param ProductApiQueryContainerInterface $queryContainer
     * @param TransferMapperInterface $transferMapper
     * @param ProductApiToProductInterface $productFacade
     * @param LocaleFacadeInterface $localeFacade
     * @param ProductCategoryFacadeInterface $productCategoryFacade
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
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @return ApiItemTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer
    {
        $query = $this->buildQuery($apiRequestTransfer);
        $localeTransfer = $this->localeFacade->getLocale($this->getLanguage($apiRequestTransfer));

        /** @var ProductsResponseApiTransfer $collection */
        $responseApiTransfer = $this->transferMapper->toTransferCollection(
            $query->find()->toArray(),
            $localeTransfer,
            $apiRequestTransfer->getResource()
        );

        $productsApiTransfers = new ArrayObject();
        /** @var ProductApiTransfer $productApiTransfer */
        foreach ($responseApiTransfer->getProducts() as $k => $productApiTransfer) {
            $productsApiTransfers[] = $this->get($productApiTransfer->getProductId(), $localeTransfer);
        }
        $responseApiTransfer->setProducts($productsApiTransfers);

        return $this->apiQueryContainer->createApiItem($responseApiTransfer->toArray(true, true));
    }

    /**
     * @param int $idProductAbstract
     * @param LocaleTransfer $localeTransfer
     *
     * @return ProductApiTransfer
     */
    protected function get(
        int $idProductAbstract,
        LocaleTransfer $localeTransfer
    )
    {
        $productTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);
        $productUrl = $this->productFacade->getProductUrl($productTransfer);
        $productCategoryTransferCollection = $this->productCategoryFacade->getCategoryTransferCollectionByIdProductAbstract(
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
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @return SpyProductAbstractQuery
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
        }
    }

    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     * @return string|null
     */
    protected function getLanguage(ApiRequestTransfer $apiRequestTransfer): ?string
    {
        $headerData = array_change_key_case($apiRequestTransfer->getHeaderData(), CASE_UPPER);
        return $headerData[ProductApiConstants::X_SPRYKER_LANGUAGE][0] ?? null;
    }
}
