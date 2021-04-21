<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerBalance;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Client\Currency\CurrencyClient;
use Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface;
use Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapperInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;

class CustomerBalanceRequestHandler implements CustomerBalanceRequestHandlerInterface
{
    private const PLACEHOLDER_URL_CUSTOMER_ID = '{customerID}';
    private const PLACEHOLDER_URL_CURRENCY_CODE = '{targetCurrency}';

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface
     */
    private $request;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface
     */
    private $accessToken;

    /**
     * @var \Pyz\Client\Currency\CurrencyClient
     */
    private $currencyClient;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapperInterface
     */
    private $mapper;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $config;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface $request
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface $accessToken
     * @param \Pyz\Client\Currency\CurrencyClient $currencyClient
     * @param \Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapperInterface $mapper
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $config
     */
    public function __construct(
        RequestInterface $request,
        AccessTokenInterface $accessToken,
        CurrencyClient $currencyClient,
        CustomerBalanceMapperInterface $mapper,
        MyWorldMarketplaceApiConfig $config
    ) {
        $this->request = $request;
        $this->accessToken = $accessToken;
        $this->currencyClient = $currencyClient;
        $this->mapper = $mapper;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $currencyCode
     *
     * @return array
     */
    public function getCustomerBalancesByCurrency(CustomerTransfer $customerTransfer, ?string $currencyCode = null): array
    {
        $this->assertCustomerTransfer($customerTransfer);
        $currencyCode = $currencyCode ?: $this->getStoreCurrency();
        $apiResponseTransfer = $this->request->request(
            $this->getRequestUrl($customerTransfer->getMyWorldCustomerId(), $currencyCode),
            $this->getRequestParams($this->getAccessToken()),
            'GET'
        );

        if (!$apiResponseTransfer->getIsSuccess()) {
            return [];
        }

        return $this->mapper->mapCustomerBalanceByCurrencyData($apiResponseTransfer->getData());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    private function assertCustomerTransfer(CustomerTransfer $customerTransfer): void
    {
        $customerTransfer->requireMyWorldCustomerId();
    }

    /**
     * @return string
     */
    private function getAccessToken(): string
    {
        $accessTokenTransfer = $this->accessToken->getAccessToken();
        $accessTokenTransfer->requireAccessToken();

        return $accessTokenTransfer->getAccessToken();
    }

    /**
     * @param string $customerId
     * @param string $currencyCode
     *
     * @return string
     */
    private function getRequestUrl(string $customerId, string $currencyCode): string
    {
        return strtr(
            $this->config->getCustomerBalanceByCurrencyUrl(),
            [
                self::PLACEHOLDER_URL_CUSTOMER_ID => $customerId,
                self::PLACEHOLDER_URL_CURRENCY_CODE => $currencyCode,
            ]
        );
    }

    /**
     * @return string
     */
    private function getStoreCurrency(): string
    {
        return $this->currencyClient->getCurrent()->getCode();
    }

    /**
     * @param string $accessToken
     *
     * @return array[]
     */
    private function getRequestParams(string $accessToken): array
    {
        return [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $accessToken),
            ],
        ];
    }
}
