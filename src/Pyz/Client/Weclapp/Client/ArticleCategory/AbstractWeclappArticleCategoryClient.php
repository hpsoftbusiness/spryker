<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory;

use GuzzleHttp\ClientInterface;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Weclapp\Client\AbstractWeclappClient;
use Pyz\Client\Weclapp\WeclappConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

abstract class AbstractWeclappArticleCategoryClient extends AbstractWeclappClient
{
    /**
     * @var \Pyz\Client\Weclapp\Client\ArticleCategory\ArticleCategoryHydratorInterface
     */
    protected $articleCategoryHydrator;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Pyz\Client\Weclapp\WeclappConfig $weclappConfig
     * @param \Pyz\Client\ApiLog\ApiLogClientInterface $apiLogClient
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     * @param \Pyz\Client\Weclapp\Client\ArticleCategory\ArticleCategoryHydratorInterface $articleCategoryHydrator
     */
    public function __construct(
        ClientInterface $httpClient,
        WeclappConfig $weclappConfig,
        ApiLogClientInterface $apiLogClient,
        ErrorLoggerInterface $errorLogger,
        ArticleCategoryHydratorInterface $articleCategoryHydrator
    ) {
        parent::__construct($httpClient, $weclappConfig, $apiLogClient, $errorLogger);
        $this->articleCategoryHydrator = $articleCategoryHydrator;
    }
}
