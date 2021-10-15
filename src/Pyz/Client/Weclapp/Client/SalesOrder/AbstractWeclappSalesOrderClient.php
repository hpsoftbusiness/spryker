<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\SalesOrder;

use GuzzleHttp\ClientInterface;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Weclapp\Client\AbstractWeclappClient;
use Pyz\Client\Weclapp\WeclappConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

abstract class AbstractWeclappSalesOrderClient extends AbstractWeclappClient
{
    /**
     * @var \Pyz\Client\Weclapp\Client\SalesOrder\SalesOrderMapperInterface
     */
    protected $salesOrderMapper;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Pyz\Client\Weclapp\WeclappConfig $weclappConfig
     * @param \Pyz\Client\ApiLog\ApiLogClientInterface $apiLogClient
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     * @param \Pyz\Client\Weclapp\Client\SalesOrder\SalesOrderMapperInterface $salesOrderMapper
     */
    public function __construct(
        ClientInterface $httpClient,
        WeclappConfig $weclappConfig,
        ApiLogClientInterface $apiLogClient,
        ErrorLoggerInterface $errorLogger,
        SalesOrderMapperInterface $salesOrderMapper
    ) {
        parent::__construct($httpClient, $weclappConfig, $apiLogClient, $errorLogger);
        $this->salesOrderMapper = $salesOrderMapper;
    }
}
