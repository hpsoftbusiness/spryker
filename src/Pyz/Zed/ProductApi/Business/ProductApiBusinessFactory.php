<?php

namespace Pyz\Zed\ProductApi\Business;

use Pyz\Zed\Api\Business\ApiFacadeInterface;
use Pyz\Zed\ProductApi\Business\Mapper\TransferMapper;
use Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Pyz\Zed\ProductApi\Business\Model\ProductApi;
use Pyz\Zed\ProductApi\Business\Model\ProductApiInterface;
use Pyz\Zed\ProductApi\Business\Model\Validator\LanguageValidator;
use Pyz\Zed\ProductApi\Business\Model\Validator\LanguageValidatorInterface;
use Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface;
use Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;
use Pyz\Zed\ProductApi\ProductApiDependencyProvider;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class ProductApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return ProductApiInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createProductApi(): ProductApiInterface
    {
        return new ProductApi(
            $this->getApiQueryContainer(),
            $this->getQueryContainer(),
            $this->createTransferMapper(),
            $this->getProductFacade(),
            $this->getLocaleFacade(),
            $this->getProductCategoryFacade()
        );
    }

    /**
     * @return TransferMapperInterface
     */
    public function createTransferMapper(): TransferMapperInterface
    {
        return new TransferMapper();
    }

    /**
     * @return ProductApiToApiInterface
     */
    protected function getApiQueryContainer(): ProductApiToApiInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return ProductApiToProductInterface
     */
    protected function getProductFacade(): ProductApiToProductInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return ApiFacadeInterface
     */
    public function getApiFacade(): ApiFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_API);
    }

    /**
     * @return LanguageValidatorInterface
     */
    public function createLanguageValidator(): LanguageValidatorInterface
    {
        return new LanguageValidator();
    }

    /**
     * @return LocaleFacadeInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return ProductCategoryFacadeInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getProductCategoryFacade(): ProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }
}
