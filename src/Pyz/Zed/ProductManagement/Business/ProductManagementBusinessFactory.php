<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business;

use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManager;
use Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManagerInterface;
use Pyz\Zed\ProductManagement\Business\GroupAction\GroupActionManager;
use Pyz\Zed\ProductManagement\Business\GroupAction\GroupActionManagerInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory as SprykerProductManagementBusinessFactory;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface getProductFacade()
 */
class ProductManagementBusinessFactory extends SprykerProductManagementBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManagerInterface
     */
    public function createDefaultAttributeManager(): DefaultAttributeManagerInterface
    {
        return new DefaultAttributeManager($this->getSpyProductManagementAttributeQuery());
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function getSpyProductManagementAttributeQuery(): SpyProductAttributeKeyQuery
    {
        return $this->getQueryContainer()->queryProductAttributeKey();
    }

    /**
     * @return \Pyz\Zed\ProductManagement\Business\GroupAction\GroupActionManagerInterface
     */
    public function createGroupActionManager(): GroupActionManagerInterface
    {
        return new GroupActionManager($this->getProductFacade());
    }
}
