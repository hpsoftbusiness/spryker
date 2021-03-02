<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
     * @return void
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
