<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete;

use Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductListProductConcreteAttributesExtractorStep extends AttributesExtractorStep
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        parent::execute($dataSet);

        $dataSet[static::KEY_ATTRIBUTES] = array_intersect_key(
            $dataSet[static::KEY_ATTRIBUTES],
            array_flip($this->getAttributesList())
        );
    }

    /**
     * @return string
     */
    protected function getAttributeKeyPrefix(): string
    {
        return 'product.attribute_key_';
    }

    /**
     * @return string
     */
    protected function getAttributeValuePrefix(): string
    {
        return 'product.value_';
    }

    /**
     * @return string[]
     */
    protected function getAttributesList(): array
    {
        return [
            'customer_group_1',
            'customer_group_2',
            'customer_group_3',
            'customer_group_4',
            'customer_group_5',
        ];
    }
}
