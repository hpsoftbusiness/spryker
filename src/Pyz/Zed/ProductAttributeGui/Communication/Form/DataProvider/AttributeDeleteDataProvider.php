<?php declare(strict_types = 1);

namespace Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider;

class AttributeDeleteDataProvider
{
    public const DATA_CLASS = 'data_class';

    /**
     * @param int $idProductManagementAttribute
     *
     * @return array
     */
    public function getData(int $idProductManagementAttribute)
    {
        return [
            'id_product_management_attribute' => $idProductManagementAttribute,
        ];
    }
    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
