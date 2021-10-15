<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Weclapp\Client\AbstractWeclappClient;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClientInterface;
use Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClientInterface;
use Pyz\Client\Weclapp\WeclappConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

abstract class AbstractWeclappArticleClient extends AbstractWeclappClient
{
    protected const CUSTOMS_TARIFF_NUMBER_NOT_FOUND_MESSAGE = 'customsTariffNumber not found';
    protected const MANUFACTURER_NOT_FOUND_MESSAGE = 'manufacturer not found';

    /**
     * @var \Pyz\Client\Weclapp\Client\Article\ArticleHydratorInterface
     */
    protected $articleHydrator;

    /**
     * @var \Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClientInterface
     */
    protected $postCustomsTariffNumberClient;

    /**
     * @var \Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClientInterface
     */
    protected $postManufacturerClient;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Pyz\Client\Weclapp\WeclappConfig $weclappConfig
     * @param \Pyz\Client\ApiLog\ApiLogClientInterface $apiLogClient
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     * @param \Pyz\Client\Weclapp\Client\Article\ArticleHydratorInterface $articleHydrator
     * @param \Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClientInterface $postCustomsTariffNumberClient
     * @param \Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClientInterface $postManufacturerClient
     */
    public function __construct(
        ClientInterface $httpClient,
        WeclappConfig $weclappConfig,
        ApiLogClientInterface $apiLogClient,
        ErrorLoggerInterface $errorLogger,
        ArticleHydratorInterface $articleHydrator,
        PostCustomsTariffNumberClientInterface $postCustomsTariffNumberClient,
        PostManufacturerClientInterface $postManufacturerClient
    ) {
        parent::__construct($httpClient, $weclappConfig, $apiLogClient, $errorLogger);
        $this->articleHydrator = $articleHydrator;
        $this->postCustomsTariffNumberClient = $postCustomsTariffNumberClient;
        $this->postManufacturerClient = $postManufacturerClient;
    }

    /**
     * @param string $requestMethod
     * @param string $actionUrl
     * @param array|null $requestBody
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    protected function callWeclapp(
        string $requestMethod,
        string $actionUrl,
        ?array $requestBody = null
    ): ?ResponseInterface {
        try {
            return parent::callWeclapp($requestMethod, $actionUrl, $requestBody);
        } catch (GuzzleException $exception) {
            $retry = false;

            if ($requestBody) {
                // add customs tariff number and retry
                if (strpos($exception->getMessage(), static::CUSTOMS_TARIFF_NUMBER_NOT_FOUND_MESSAGE) !== false) {
                    $this->postCustomsTariffNumberClient
                        ->postCustomsTariffNumber($this->articleHydrator->mapWeclappDataToWeclappArticle($requestBody));
                    $retry = true;
                }

                // add manufacturer and retry
                if (strpos($exception->getMessage(), static::MANUFACTURER_NOT_FOUND_MESSAGE) !== false) {
                    $this->postManufacturerClient
                        ->postManufacturer($this->articleHydrator->mapWeclappDataToWeclappArticle($requestBody));
                    $retry = true;
                }
            }

            if ($retry) {
                return $this->callWeclapp($requestMethod, $actionUrl, $requestBody);
            }

            throw $exception;
        }
    }
}
