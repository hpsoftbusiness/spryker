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
            if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+)$/', $key, $match)) {
                continue;
            }

            $attributeValueKey = $this->getAttributeValuePrefix() . $match[1];
            $attributeKey = trim(strtolower(str_replace(' ', '_', $value)));
            $attributeValue = trim($dataSet[$attributeValueKey]);

            if ($attributeKey !== '') {
                $isAffiliateAttribute = in_array($attributeKey, $this->getAffiliateAttributeList());

                if (!$isAffiliateAttribute) {
                    if (strtoupper($attributeValue) === 'TRUE' || strtoupper($attributeValue) === 'FALSE' || $attributeValue === '0' || $attributeValue === '1' || $attributeValue === "") {
                        $attributes[$attributeKey] = strtoupper($attributeValue) === 'TRUE' || $attributeValue === '1';
                    } else {
                        if ($attributeKey === 'cashback_amount') {
                            $attributeValue = (float)str_replace(',', '.', $attributeValue) * 100;
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
