<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ApiKeyAuthRestApi;

use Pyz\Glue\ApiKeyAuthRestApi\Processor\ApiKeyUserFinder;
use Pyz\Glue\ApiKeyAuthRestApi\Processor\ApiKeyUserFinderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Pyz\Glue\ApiKeyAuthRestApi\ApiKeyAuthRestApiConfig getConfig()
 */
class ApiKeyAuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Glue\ApiKeyAuthRestApi\Processor\ApiKeyUserFinderInterface
     */
    public function createApiKeyUserFinder(): ApiKeyUserFinderInterface
    {
        return new ApiKeyUserFinder(
            $this->getConfig()
        );
    }
}
