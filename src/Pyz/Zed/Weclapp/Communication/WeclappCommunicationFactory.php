<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication;

use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\Weclapp\Communication\Plugin\Event\Mapper\WeclappWebhooksAttributesMapper;
use Pyz\Zed\Weclapp\Communication\Plugin\Event\Mapper\WeclappWebhooksAttributesMapperInterface;
use Pyz\Zed\Weclapp\WeclappDependencyProvider;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface getRepository()
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 */
class WeclappCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): EventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Pyz\Zed\Weclapp\Communication\Plugin\Event\Mapper\WeclappWebhooksAttributesMapperInterface
     */
    public function createWeclappWebhookAttributesMapper(): WeclappWebhooksAttributesMapperInterface
    {
        return new WeclappWebhooksAttributesMapper();
    }

    /**
     * @return \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_PRODUCT);
    }
}
