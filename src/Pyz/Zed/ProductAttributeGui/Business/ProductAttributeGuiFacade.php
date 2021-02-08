<?php

namespace Pyz\Zed\ProductAttributeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade as SprykerAbstractFacade;

/**
 * @method \Pyz\Zed\ProductAttributeGui\Business\ProductAttributeGuiBusinessFactory getFactory()
 */
class ProductAttributeGuiFacade extends SprykerAbstractFacade implements ProductAttributeGuiFacadeInterface
{
    /**
     * @param int $idProductManagementAttribute
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function delete(int $idProductManagementAttribute): void
    {
        $this
            ->getFactory()
            ->createProductAttributeWriter()
            ->delete($idProductManagementAttribute);
    }
}
