<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ApiKeyAuthRestApi\Plugin;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Pyz\Glue\ApiKeyAuthRestApi\ApiKeyAuthRestApiFactory getFactory()
 */
class RestUserFinderByApiKeyPlugin extends AbstractPlugin implements RestUserFinderPluginInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function findUser(RestRequestInterface $restRequest): ?RestUserTransfer
    {
        return $this
            ->getFactory()
            ->createApiKeyUserFinder()
            ->findUser($restRequest);
    }
}
