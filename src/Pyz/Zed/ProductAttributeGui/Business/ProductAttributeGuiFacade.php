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
        $this->getFactory()
            ->getProductAttributeQueryContainer()
            ->deleteProductAttribute($idProductManagementAttribute);
    }

    /**
     * @param string $idProductAttributeKey
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @return bool
     */
    public function isProductAttributeCanBeDeleted(string $idProductAttributeKey): bool
    {
        $queryContainer = $this->getFactory()
            ->getProductQueryContainer();

        return $queryContainer->countUsesOfProductAttributeByProduct($idProductAttributeKey) === 0 &&
            $queryContainer->countUsesOfProductAttributeByAbstractProduct($idProductAttributeKey) === 0;
    }
}
