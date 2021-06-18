<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer;
use Orm\Zed\ProductAbstractAttribute\Persistence\PyzProductAbstractAttribute;

class ProductAbstractAttributeMapper
{
    /**
     * @param \Orm\Zed\ProductAbstractAttribute\Persistence\PyzProductAbstractAttribute $pyzProductAbstractAttribute
     * @param \Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer $entityTransfer
     *
     * @return \Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer
     */
    public function mapEntityToEntityTransfer(
        PyzProductAbstractAttribute $pyzProductAbstractAttribute,
        PyzProductAbstractAttributeEntityTransfer $entityTransfer
    ): PyzProductAbstractAttributeEntityTransfer {
        return $entityTransfer->fromArray(
            $pyzProductAbstractAttribute->toArray(),
            true
        );
    }
}
