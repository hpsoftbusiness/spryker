<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use DateTime;
use Exception;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

class CreateTurnoverRequest implements TurnoverRequestInterface
{
    protected const SEGMENT_NUMBER = 1;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $myWorldMarketplaceApiClient;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

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
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager
     * @param \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     */
    public function __construct(
        MyWorldMarketplaceApiClientInterface $myWorldMarketplaceApiClient,
        CustomerFacadeInterface $customerFacade,
        UtilEncodingServiceInterface $utilEncodingService,
        MyWorldMarketplaceApiEntityManagerInterface $myWorldMarketplaceApiEntityManager,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
    ) {
        $this->myWorldMarketplaceApiClient = $myWorldMarketplaceApiClient;
        $this->customerFacade = $customerFacade;
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

        $this->myWorldMarketplaceApiEntityManager->setIsTurnoverCreated($orderTransfer->getOrderReference());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function buildRequestUrl(OrderTransfer $orderTransfer): string
    {
        $customerTransfer = $this->customerFacade->findByReference($orderTransfer->getCustomerReference());

        if (!$customerTransfer) {
            throw new Exception('Customer not found.');
        }

        $customerId = $customerTransfer->getMyWorldCustomerId() ?? $customerTransfer->getMyWorldCustomerNumber();
        if (!$customerId) {
            throw new Exception('Customer ID or number required.');
        }

        return sprintf('%s/customers/%s/turnovers', $this->myWorldMarketplaceApiConfig->getApiUrl(), $customerId);
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
            'Reference' => $this->getTurnoverReference($orderTransfer),
            'Date' => date(DateTime::ISO8601, strtotime($orderTransfer->getCreatedAt())),
            'Amount' => (string)bcdiv($orderTransfer->getTotals()->getPriceToPay(), 100, 2),
            'Currency' => $orderTransfer->getCurrencyIsoCode(),
            'SegmentNumber' => static::SEGMENT_NUMBER,
            'ProfileIdentifier' => $this->myWorldMarketplaceApiConfig->getDealerId(),
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
