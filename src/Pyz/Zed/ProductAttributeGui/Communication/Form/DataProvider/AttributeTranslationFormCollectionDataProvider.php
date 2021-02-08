<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider;

use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeValueTranslationForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider as SprykerAttributeTranslationFormCollectionDataProvider;

class AttributeTranslationFormCollectionDataProvider extends SprykerAttributeTranslationFormCollectionDataProvider
{
    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     *
     * @return array
     */
    protected function getValueTranslations($idProductManagementAttribute, $idLocale): array
    {
        $attributeValueEntities = $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale, '', null, null)
            ->find();

        $attributeValues = [];
        foreach ($attributeValueEntities as $attributeValueEntity) {
            $attributeValues[] = [
                AttributeValueTranslationForm::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE => $attributeValueEntity->getIdProductManagementAttributeValue(),
                AttributeValueTranslationForm::FIELD_VALUE => $attributeValueEntity->getValue(),
                AttributeValueTranslationForm::FIELD_TRANSLATION => $attributeValueEntity->getVirtualColumn('translation'),
                AttributeValueTranslationForm::FIELD_FK_LOCALE => $attributeValueEntity->getVirtualColumn('fk_locale'),
            ];
        }

        return $attributeValues;
    }
}
