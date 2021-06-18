<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Persistence;

use Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributePersistenceFactory getFactory()
 */
class ProductAbstractAttributeEntityManager extends AbstractEntityManager implements ProductAbstractAttributeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer $productAbstractAttributeEntityTransfer
     *
     * @return void
     */
    public function saveProductAbstractAttribute(PyzProductAbstractAttributeEntityTransfer $productAbstractAttributeEntityTransfer): void
    {
        $this->save($productAbstractAttributeEntityTransfer);
    }
}
