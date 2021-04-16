<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage;

use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductImageHydratorStep extends ProductImageHydratorStep
{
    public const BULK_SIZE = 100;

    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    public const COLUMN_IMAGE_SET_NAME = 'product_image.image_set_name';
    public const COLUMN_EXTERNAL_URL_LARGE = 'product_image.external_url_large';
    public const COLUMN_EXTERNAL_URL_SMALL = 'product_image.external_url_small';
    public const COLUMN_LOCALE = 'product_image.locale';
    public const COLUMN_SORT_ORDER = 'product_image.sort_order';
    public const COLUMN_PRODUCT_IMAGE_KEY = 'product_image.product_image_key';
    public const COLUMN_PRODUCT_IMAGE_SET_KEY = 'product_image.product_image_set_key';

    public const COLUMN_ASSIGNED_PRODUCT_TYPE = 'product_image.assigned_product_type';

    protected const ASSIGNABLE_PRODUCT_TYPE_ABSTRACT = 'abstract';
    protected const ASSIGNABLE_PRODUCT_TYPE_CONCRETE = 'concrete';

    public const COLUMN_IS_AFFILIATE = 'product.value_73';

    protected const ASSIGNABLE_PRODUCT_TYPES = [
        self::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        self::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet = $this->assignProductType($dataSet);

        parent::execute($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function assignProductType(DataSetInterface $dataSet): DataSetInterface
    {
        if (!empty($dataSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT])) {
            $dataSet[static::COLUMN_CONCRETE_SKU] = $dataSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT];
        }

        if (!empty($dataSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT])) {
            $dataSet[static::COLUMN_ABSTRACT_SKU] = $dataSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT];
        }

        return $dataSet;
    }
}
