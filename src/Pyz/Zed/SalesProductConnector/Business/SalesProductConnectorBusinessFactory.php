<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business;

use Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpander;
use Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpanderInterface;
use Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory as SprykerSalesProductConnectorBusinessFactory;

class SalesProductConnectorBusinessFactory extends SprykerSalesProductConnectorBusinessFactory
{
    /**
     * @return \Pyz\Zed\SalesProductConnector\Business\Expander\ProductAttributesExpanderInterface
     */
    public function createProductAttributesExpander(): ProductAttributesExpanderInterface
    {
        return new ProductAttributesExpander(
            $this->getRepository(),
            $this->getUtilEncodingService()
        );
    }
}
