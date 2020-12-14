<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication;

use Pyz\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper;
use Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory as SprykerProductManagementCommunicationFactory;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()()
 */
class ProductManagementCommunicationFactory extends SprykerProductManagementCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper
     */
    public function createProductFormTransferGenerator()
    {
        return new ProductFormTransferMapper(
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createLocaleProvider(),
            $this->getProductFormTransferMapperExpanderPlugins(),
            $this->createProductConcreteSuperAttributeFilterHelper()
        );
    }
}
