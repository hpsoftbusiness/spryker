<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\ProductGroupActionTransfer;
use Pyz\Shared\ProductManagement\ProductManagementConstants;
use Pyz\Zed\ProductManagement\Communication\Table\ProductTable;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper as SprykerProductFormTransferMapper;
use Symfony\Component\Form\FormInterface;

class ProductFormTransferMapper extends SprykerProductFormTransferMapper implements ProductFormTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string|null $requestIds
     *
     * @return \Generated\Shared\Transfer\ProductGroupActionTransfer
     */
    public function buildGroupActionTransfer(FormInterface $form, ?string $requestIds): ProductGroupActionTransfer
    {
        $transfer = new ProductGroupActionTransfer();
        $transfer->setAction($this->getProductGroupAction($form));
        $transfer->setIds($this->getProductIds($requestIds));

        return $transfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return string|null
     */
    protected function getProductGroupAction(FormInterface $form): ?string
    {
        $formData = $form->getData();
        $possibleActions = [
            ProductManagementConstants::GROUP_ACTION_ACTIVATE,
            ProductManagementConstants::GROUP_ACTION_DEACTIVATE,
            ProductManagementConstants::GROUP_ACTION_DELETE,
        ];

        foreach ($possibleActions as $action) {
            if ($formData[$action] ?? false) {
                return $action;
            }
        }

        return null;
    }

    /**
     * @param string|null $requestIds
     *
     * @return int[]
     */
    protected function getProductIds(?string $requestIds): array
    {
        return array_filter(
            array_map(
                'intval',
                explode(
                    ',',
                    str_replace(
                        ProductTable::SELECT_CHECKBOX_ID_PREFIX,
                        '',
                        $requestIds
                    )
                )
            )
        );
    }
}
