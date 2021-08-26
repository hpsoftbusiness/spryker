<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\CancelTurnoverRequest;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\CreateTurnoverRequest;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelper;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelperInterface;
use Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestInterface;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface getEntityManager()
 */
class MyWorldMarketplaceApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestInterface
     */
    public function createCreateTurnoverRequest(int $orderItemId, OrderTransfer $orderTransfer): TurnoverRequestInterface
    {
        return CreateTurnoverRequest::create(
            $this->getConfig(),
            $this->getMyWorldMarketplaceApiClient(),
            $this->getUtilEncodingService(),
            $this->getEntityManager(),
            $this->createTurnoverRequestHelper(),
            $orderTransfer,
            $orderItemId
        );
    }

    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestInterface
     */
    public function createCancelTurnoverRequest(int $orderItemId, OrderTransfer $orderTransfer): TurnoverRequestInterface
    {
        return CancelTurnoverRequest::create(
            $this->getConfig(),
            $this->getMyWorldMarketplaceApiClient(),
            $this->getUtilEncodingService(),
            $this->getEntityManager(),
            $this->createTurnoverRequestHelper(),
            $orderTransfer,
            $orderItemId
        );
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    public function getMyWorldMarketplaceApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::CLIENT_MY_WORLD_MARKETPLACE_API);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelperInterface
     */
    protected function createTurnoverRequestHelper(): TurnoverRequestHelperInterface
    {
        return new TurnoverRequestHelper($this->getCustomerFacade(), $this->getConfig());
    }
}
