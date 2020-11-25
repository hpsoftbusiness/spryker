<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Product;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AttributesExtractorStep implements DataImportStepInterface
{
    public const KEY_ATTRIBUTES = 'attributes';
    public const KEY_HIDDEN_ATTRIBUTES = 'hidden_attributes';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $keysToUnset = [];
        $attributes = [];
        $hiddenAttributes = [];

        foreach ($dataSet as $key => $value) {
            if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+)$/', $key, $match)) {
                continue;
            }

            $attributeValueKey = $this->getAttributeValuePrefix() . $match[1];
            $attributeKey = trim($value);
            $attributeValue = trim($dataSet[$attributeValueKey]);

            if ($attributeKey !== '') {
                if (in_array($attributeKey, $this->getAttributeList())) {
                    $attributes[$attributeKey] = $attributeValue;
                }

                $hiddenAttributes[$attributeKey] = $attributeValue;
            }

            $keysToUnset[] = $match[0];
            $keysToUnset[] = $attributeValueKey;
        }

        foreach ($keysToUnset as $key) {
            unset($dataSet[$key]);
        }

        $dataSet[static::KEY_ATTRIBUTES] = $attributes;
        $dataSet[static::KEY_HIDDEN_ATTRIBUTES] = $hiddenAttributes;
    }

    /**
     * @return string
     */
    protected function getAttributeKeyPrefix(): string
    {
        return 'attribute_key_';
    }

    /**
     * @return string
     */
    protected function getAttributeValuePrefix(): string
    {
        return 'value_';
    }

    /**
     * @return string[]
     */
    public function getAttributeList(): array
    {
        return [
            'mpn',
            'ean',
            'color',
            'size',
            'material',
            'manufacturer',
            'brand',
            'length',
            'width',
            'height',
            'weight',
        ];
    }
}
