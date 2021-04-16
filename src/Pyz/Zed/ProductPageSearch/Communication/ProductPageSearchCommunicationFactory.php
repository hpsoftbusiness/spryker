<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication;

use Pyz\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory as SprykerProductPageSearchCommunicationFactory;

class ProductPageSearchCommunicationFactory extends SprykerProductPageSearchCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_EVENT);
    }
}
