<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business;

use Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpander;
use Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpanderInterface;
use Pyz\Zed\SalesProductConnector\SalesProductConnectorDependencyProvider;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory as SprykerSalesProductConnectorBusinessFactory;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class SalesProductConnectorBusinessFactory extends SprykerSalesProductConnectorBusinessFactory
{
    /**
     * @return \Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpanderInterface
     */
    public function createProductAttributesExpander(): ProductAttributesExpanderInterface
    {
        return new ProductAttributesExpander(
            $this->getRepository(),
            $this->getUtilEncodingService(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::FACADE_LOCALE);
    }
}
