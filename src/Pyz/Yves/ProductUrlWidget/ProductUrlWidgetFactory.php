<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget;

use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface;
use Pyz\Yves\ProductDetailPage\ProductDetailPageDependencyProvider;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductUrlWidgetFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(ProductUrlWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface
     */
    public function getProductAffiliateService(): ProductAffiliateServiceInterface
    {
        return $this->getProvidedDependency(ProductDetailPageDependencyProvider::SERVICE_PRODUCT_AFFILIATE);
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductUrlWidgetDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOfferStorageClient
    {
        return $this->getProvidedDependency(
            ProductUrlWidgetDependencyProvider::MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(ProductUrlWidgetDependencyProvider::STORE);
    }
}
