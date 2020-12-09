<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business;

use Pyz\Zed\PostingExport\Business\ContentBuilder\PostingExportContentBuilder;
use Pyz\Zed\PostingExport\PostingExportDependencyProvider;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Pyz\Zed\SalesOrderUid\Business\SalesOrderUidFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;

/**
 * @method \Pyz\Zed\PostingExport\PostingExportConfig getConfig()
 */
class PostingExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\PostingExport\Business\ContentBuilder\PostingExportContentBuilder
     */
    public function createPostingExportContentBuilder(): PostingExportContentBuilder
    {
        return new PostingExportContentBuilder(
            $this->getSalesFacade(),
            $this->getMoneyFacade(),
            $this->getLocaleFacade(),
            $this->getSalesOrderUidFacade(),
            $this->getStoreClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    public function getMoneyFacade(): MoneyFacadeInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Pyz\Zed\SalesOrderUid\Business\SalesOrderUidFacadeInterface
     */
    public function getSalesOrderUidFacade(): SalesOrderUidFacadeInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::FACADE_SALES_ORDER_UID);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(PostingExportDependencyProvider::CLIENT_STORE);
    }
}
