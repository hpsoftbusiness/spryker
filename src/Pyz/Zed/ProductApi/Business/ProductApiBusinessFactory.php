<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business;

use Pyz\Zed\Api\Business\ApiFacadeInterface;
use Pyz\Zed\PriceProduct\Business\PriceProductFacade;
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

/**
 * @method \Pyz\Zed\ProductApi\ProductApiConfig getConfig()
 * @method \Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface getQueryContainer()
 */
class ProductApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductApi\Business\Model\ProductApiInterface
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
     * @return \Pyz\Zed\ProductApi\Business\Mapper\TransferMapperInterface
     */
    public function createTransferMapper(): TransferMapperInterface
    {
        return new TransferMapper($this->getPriceProductFacade());
    }

    /**
     * @return \Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected function getApiQueryContainer(): ProductApiToApiInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return \Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface
     */
    protected function getProductFacade(): ProductApiToProductInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Pyz\Zed\Api\Business\ApiFacadeInterface
     */
    public function getApiFacade(): ApiFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_API);
    }

    /**
     * @return \Pyz\Zed\ProductApi\Business\Model\Validator\LanguageValidatorInterface
     */
    public function createLanguageValidator(): LanguageValidatorInterface
    {
        return new LanguageValidator();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Pyz\Zed\PriceProduct\Business\PriceProductFacade
     */
    protected function getPriceProductFacade(): PriceProductFacade
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
