<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade as SprykerProductAttributeFacade;

/**
 * @method \Pyz\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Pyz\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeFacade extends SprykerProductAttributeFacade implements ProductAttributeFacadeInterface
{
    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function findProductManagementAttributeByKey(string $key): ?ProductManagementAttributeTransfer
    {
        return $this->getFactory()->createAttributeReader()->getAttributeByKey($key);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array
    {
        return $this->getFactory()->createAttributeReader()->getProductSuperAttributeCollection();
    }
}
