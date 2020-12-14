<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper as SprykerProductFormTransferMapper;
use Symfony\Component\Form\FormInterface;

class ProductFormTransferMapper extends SprykerProductFormTransferMapper
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int|null $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProductConcreteTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        FormInterface $form,
        $idProduct = null
    ) {
        $productConcreteTransfer = parent::buildProductConcreteTransfer($productAbstractTransfer, $form, $idProduct);

        return $productConcreteTransfer
            ->setHiddenAttributes($this->getHiddenAttributes($idProduct));
    }

    /**
     * @param int|null $idProduct
     *
     * @return array
     */
    public function getHiddenAttributes(?int $idProduct): array
    {
        $hiddenAttributes = [];

        $entity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->findOne();

        if ($entity) {
            $hiddenAttributes = json_decode($entity->getHiddenAttributes(), true);
        }

        return $hiddenAttributes;
    }
}
