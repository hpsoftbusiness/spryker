<?php
declare(strict_types = 1 );

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ApiKeyAuthRestApi\Processor;

use Generated\Shared\Transfer\RestUserTransfer;
use Pyz\Glue\ApiKeyAuthRestApi\ApiKeyAuthRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ApiKeyUserFinder implements ApiKeyUserFinderInterface
{
    /**
     * @var \Pyz\Glue\ApiKeyAuthRestApi\ApiKeyAuthRestApiConfig
     */
    protected $apiKeyAuthRestApiConfig;

    /**
     * @param \Pyz\Glue\ApiKeyAuthRestApi\ApiKeyAuthRestApiConfig $apiKeyAuthRestApiConfig
     */
    public function __construct(
        ApiKeyAuthRestApiConfig $apiKeyAuthRestApiConfig
    ) {
        $this->apiKeyAuthRestApiConfig = $apiKeyAuthRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        $apiKey = $restRequest
            ->getHttpRequest()
            ->headers
            ->get(
                $this
                    ->apiKeyAuthRestApiConfig
                    ->getHeaderApiKey()
            );

        if ($apiKey === null) {
            return null;
        }

        if ($apiKey !== $this->apiKeyAuthRestApiConfig->getApiKey()) {
            return null;
        }

        $restUserTransfer = (new RestUserTransfer())
            ->setIsAuthenticatedByApiKey(true);

        return $restUserTransfer;
    }
}
