<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi;

use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Queue\QueueClientInterface;
use Pyz\Glue\WeclappRestApi\Processor\Webhooks\WeclappWebhooksProcessor;
use Pyz\Glue\WeclappRestApi\Processor\Webhooks\WeclappWebhooksProcessorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class WeclappRestApiFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Glue\WeclappRestApi\Processor\Webhooks\WeclappWebhooksProcessorInterface
     */
    public function createWeclappWebhooksProcessor(): WeclappWebhooksProcessorInterface
    {
        return new WeclappWebhooksProcessor(
            $this->getResourceBuilder(),
            $this->getQueueClient(),
            $this->getApiLogClient()
        );
    }

    /**
     * @return \Pyz\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(WeclappRestApiDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Pyz\Client\ApiLog\ApiLogClientInterface
     */
    protected function getApiLogClient(): ApiLogClientInterface
    {
        return $this->getProvidedDependency(WeclappRestApiDependencyProvider::CLIENT_API_LOG);
    }
}
