<?php

namespace Pyz\Zed\ProductManagement\Dependency\Facade;

use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBridge as SprykerProductManagementToProductBridge;

class ProductManagementToProductBridge extends SprykerProductManagementToProductBridge
{
    /**
     * @param int $idProductAbstract
     */
    public function markProductAsRemoved(int $idProductAbstract): void
    {
        /** @var ProductFacadeInterface $productFacade */
        $productFacade = $this->productFacade;
        $productFacade->markProductAsRemoved($idProductAbstract);
    }
}
