<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider;

use Pyz\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm as SprykerAttributeForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider as SprykerAttributeFormDataProvider;

class AttributeFormDataProvider extends SprykerAttributeFormDataProvider
{
    /**
     * @param int|null $idProductManagementAttribute
     *
     * @return array
     */
    public function getData($idProductManagementAttribute = null)
    {
        if ($idProductManagementAttribute === null) {
            return [
                SprykerAttributeForm::FIELD_ALLOW_INPUT => false,
            ];
        }

        $productManagementAttributeEntity = $this->getAttributeEntity($idProductManagementAttribute);

        return [
            AttributeForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $productManagementAttributeEntity->getIdProductManagementAttribute(),
            AttributeForm::FIELD_KEY => $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey(),
            AttributeForm::FIELD_INPUT_TYPE => $productManagementAttributeEntity->getInputType(),
            AttributeForm::FIELD_ALLOW_INPUT => $productManagementAttributeEntity->getAllowInput(),
            AttributeForm::FIELD_IS_SUPER => $productManagementAttributeEntity->getSpyProductAttributeKey()->getIsSuper(),
            AttributeForm::FIELD_VALUES => array_values($this->getValues($productManagementAttributeEntity)),
            AttributeForm::FIELD_SHOW_ON_PDP => $productManagementAttributeEntity->getShowOnPdp(),
        ];
    }
}
