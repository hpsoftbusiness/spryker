<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi\Plugin;

use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Pyz\Glue\WeclappRestApi\WeclappRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class WeclappWebhooksResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection->addPost('post', false);

        return $resourceRouteCollection;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return WeclappRestApiConfig::RESOURCE_WEBHOOK;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return 'weclapp-webhooks-resource';
    }

    /**
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestWeclappWebhooksAttributesTransfer::class;
    }
}
