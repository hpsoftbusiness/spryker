<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Product;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AttributesExtractorStep implements DataImportStepInterface
{
    public const KEY_ATTRIBUTES = 'attributes';
    public const KEY_AFFILIATE_ATTRIBUTES = 'affiliate_attributes';
    public const KEY_CASHBACK_AMOUNT = 'cashback_amount';
    public const KEY_SHOPPING_POINTS = 'shopping_points';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $attributes = [];
        $affiliateAttributes = [];

        foreach ($dataSet as $key => $value) {
            if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+)$/', trim($key), $match)) {
                continue;
            }

            $attributeValueKey = $this->getAttributeValuePrefix() . $match[1];
            $attributeKey = trim(strtolower(str_replace(' ', '_', trim($value))));
            $attributeValue = trim($dataSet[$attributeValueKey]);

            if ($attributeKey !== '') {
                $isAffiliateAttribute = in_array($attributeKey, $this->getAffiliateAttributeList());

                if (!$isAffiliateAttribute) {
                    if ($attributeKey === 'cashback_amount') {
                        $attributes[$attributeKey] = (float)str_replace(',', '.', $attributeValue) * 100;
                    } elseif (strtoupper($attributeValue) === 'TRUE' || strtoupper($attributeValue) === 'FALSE' || $attributeValue === "") {
                        $attributes[$attributeKey] = strtoupper($attributeValue) === 'TRUE';
                    } else {
                        if ($attributeKey === self::KEY_CASHBACK_AMOUNT) {
                            $attributeValue = (int)((string)((float)str_replace(',', '.', $attributeValue) * 100));
                        }
                        if ($attributeKey === self::KEY_SHOPPING_POINTS) {
                            $attributeValue = (int)$attributeValue;
                        }
                        $attributes[$attributeKey] = $attributeValue;
                    }
                }

                if ($isAffiliateAttribute) {
                    $affiliateAttributes[$attributeKey] = $attributeValue;
                }
            }
        }

        $extraAttributesListKey = $this->getExtraAttributesListKeys();
        foreach ($extraAttributesListKey as $key) {
            $attributes[$key] = $dataSet[$key];
        }
        if (!in_array(self::KEY_CASHBACK_AMOUNT, array_keys($attributes))) {
            $attributes[self::KEY_CASHBACK_AMOUNT] = 0;
        }
        if (!in_array(self::KEY_SHOPPING_POINTS, array_keys($attributes))) {
            $attributes[self::KEY_SHOPPING_POINTS] = 0;
        }
        $dataSet[static::KEY_ATTRIBUTES] = $attributes;
        $dataSet[static::KEY_AFFILIATE_ATTRIBUTES] = $affiliateAttributes;
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
    public function getAffiliateAttributeList(): array
    {
        return [
            'affiliate_product',
            'affiliate_deeplink',
            'displayed_price',
            'affiliate_merchant_name',
            'affiliate_merchant_id',
            'merchant_product_id',
            'affiliate_network',
        ];
    }

    /**
     * @return string[]
     */
    protected function getExtraAttributesListKeys(): array
    {
        return [
            CombinedProductStockHydratorStep::COLUMN_NAME,
        ];
    }
}
