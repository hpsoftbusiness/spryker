<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

abstract class TurnoverRequest implements TurnoverRequestInterface
{
    use TransactionTrait;

    /**
     * @var \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $myWorldMarketplaceApiClient;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface
     */
    protected $myWorldMarketplaceApiEntityManager;

    /**
     * @var \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelperInterface
     */
    protected $turnoverRequestHelper;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransfer;

    /**
     * @param \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelperInterface $turnoverRequestHelper
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    final public function __construct(
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig,
        MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient,
        UtilEncodingServiceInterface $utilEncodingService,
        MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager,
        TurnoverRequestHelperInterface $turnoverRequestHelper,
        OrderTransfer $orderTransfer,
        ItemTransfer $itemTransfer
    ) {
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
        $this->myWorldMarketplaceApiClient = $myWorldMarketplaceApiClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->myWorldMarketplaceApiEntityManager = $myWorldMarketplaceApiEntityManager;
        $this->turnoverRequestHelper = $turnoverRequestHelper;
        $this->orderTransfer = $orderTransfer;
        $this->itemTransfer = $itemTransfer;
    }

    /**
     * @param \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Business\Request\TurnoverRequestHelperInterface $turnoverRequestHelper
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $orderItemId
     *
     * @return static
     */
    public static function create(
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig,
        MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient,
        UtilEncodingServiceInterface $utilEncodingService,
        MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager,
        TurnoverRequestHelperInterface $turnoverRequestHelper,
        OrderTransfer $orderTransfer,
        int $orderItemId
    ) {
        return new static(
            $myWorldMarketplaceApiConfig,
            $myWorldMarketplaceApiClient,
            $utilEncodingService,
            $myWorldMarketplaceApiEntityManager,
            $turnoverRequestHelper,
            $orderTransfer,
            $turnoverRequestHelper->extractOrderItemFromOrderTransferById(
                $orderItemId,
                $orderTransfer
            )
        );
    }

    /**
     * @return string
     */
    abstract protected function getUrl(): string;

    /**
     * @return array
     */
    abstract protected function getBody(): array;

    /**
     * @return void
     */
    abstract protected function onSuccess(): void;

    /**
     * @return void
     */
    public function send(): void
    {
        $myWorldMarketplaceApiResponseTransfer = $this
            ->myWorldMarketplaceApiClient
            ->performApiRequest(
                $this->getUrl(),
                $this->getRequestParams()
            );

        if ($myWorldMarketplaceApiResponseTransfer->getIsSuccess()) {
            $this->getTransactionHandler()->handleTransaction(function () {
                $this->onSuccess();
            });
        }
    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        $accessTokenTransfer = $this->myWorldMarketplaceApiClient->getAccessToken();
        $accessTokenTransfer->requireAccessToken();

        return [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $accessTokenTransfer->getAccessToken()),
                'Accept' => 'application/vnd.myworld.services-v1+json',
                'Content-Type' => 'application/json',
            ],
            'body' => $this->utilEncodingService->encodeJson($this->getBody()),
        ];
    }
}
