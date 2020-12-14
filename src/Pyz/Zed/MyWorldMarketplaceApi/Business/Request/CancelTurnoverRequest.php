<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class CancelTurnoverRequest implements TurnoverRequestInterface
{
    protected const SEGMENT_NUMBER = 1;
    protected const DATE_FORMAT = 'yyyy-MM-ddTHH:mm:ssZ';

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
     * @var \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager
     * @param \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     */
    public function __construct(
        MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient,
        UtilEncodingServiceInterface $utilEncodingService,
        MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
    ) {
        $this->myWorldMarketplaceApiClient = $myWorldMarketplaceApiClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->myWorldMarketplaceApiEntityManager = $myWorldMarketplaceApiEntityManager;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function request(OrderTransfer $orderTransfer): void
    {
        $myWorldMarketplaceApiResponseTransfer = $this->myWorldMarketplaceApiClient->performApiRequest(
            $this->buildRequestUrl($orderTransfer),
            $this->getRequestParams($orderTransfer)
        );

        if (!$myWorldMarketplaceApiResponseTransfer->getIsSuccess()) {
            return;
        }

        $this->myWorldMarketplaceApiEntityManager->setIsTurnoverCancelled($orderTransfer->getOrderReference());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function buildRequestUrl(OrderTransfer $orderTransfer): string
    {
        return sprintf(
            '%s/dealers/%s/turnovers/%s/cancel',
            $this->myWorldMarketplaceApiConfig->getApiUrl(),
            $this->myWorldMarketplaceApiConfig->getDealerId(),
            $this->getTurnoverReference($orderTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getRequestParams(OrderTransfer $orderTransfer): array
    {
        $accessTokenTransfer = $this->myWorldMarketplaceApiClient->getAccessToken();
        $accessTokenTransfer->requireAccessToken();

        $requestBody = $this->utilEncodingService->encodeJson([
            'Amount' => (string)bcdiv($orderTransfer->getTotals()->getCanceledTotal(), 100, 2),
            'Currency' => $orderTransfer->getCurrencyIsoCode(),
        ]);

        return [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $accessTokenTransfer->getAccessToken()),
                'Accept' => 'application/vnd.myworld.services-v1+json',
                'Content-Type' => 'application/json',
            ],
            'body' => $requestBody,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getTurnoverReference(OrderTransfer $orderTransfer): string
    {
        return sprintf(
            '%s-%s-%s',
            $this->myWorldMarketplaceApiConfig->getOrderReferencePrefix(),
            $orderTransfer->getOrderReference(),
            strtotime($orderTransfer->getCreatedAt())
        );
    }
}
