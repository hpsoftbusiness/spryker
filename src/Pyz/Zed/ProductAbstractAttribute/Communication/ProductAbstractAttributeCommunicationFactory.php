<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Communication;

use Pyz\Zed\ProductAbstractAttribute\ProductAbstractAttributeDependencyProvider;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Business\ProductAbstractAttributeFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface getEntityManager()
 */
class ProductAbstractAttributeCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): EventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductAbstractAttributeDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
