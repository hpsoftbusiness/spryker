<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ApiKeyAuthRestApi;

use Pyz\Shared\ApiKeyAuthRestApi\ApiKeyAuthRestApiConstants;
use Spryker\Glue\Kernel\AbstractBundleConfig;

class ApiKeyAuthRestApiConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->get(ApiKeyAuthRestApiConstants::API_KEY);
    }

    /**
     * @return string
     */
    public function getHeaderApiKey(): string
    {
        return ApiKeyAuthRestApiConstants::HEADER_API_KEY;
    }
}
