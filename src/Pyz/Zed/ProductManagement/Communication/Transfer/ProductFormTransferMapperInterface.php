<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\ProductGroupActionTransfer;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapperInterface as SprykerProductFormTransferMapperInterface;
use Symfony\Component\Form\FormInterface;

interface ProductFormTransferMapperInterface extends SprykerProductFormTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string|null $requestIds
     *
     * @return \Generated\Shared\Transfer\ProductGroupActionTransfer
     */
    public function buildGroupActionTransfer(FormInterface $form, ?string $requestIds): ProductGroupActionTransfer;
}
