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
    public const KEY_HIDDEN_ATTRIBUTES = 'hidden_attributes';
    public const KEY_AFFILIATE_ATTRIBUTES = 'affiliate_attributes';
    public const KEY_PDP_ATTRIBUTES = 'pdp_attributes';

    protected const KEY_IS_SELLABLE_PATTERN = 'sellable_';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $attributes = [];
        $hiddenAttributes = [];
        $affiliateAttributes = [];
        $pdpAttributes = [];

        foreach ($dataSet as $key => $value) {
            if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+)$/', $key, $match)) {
                continue;
            }

            $attributeValueKey = $this->getAttributeValuePrefix() . $match[1];
            $attributeKey = trim(strtolower(str_replace(' ', '_', $value)));
            $attributeValue = trim($dataSet[$attributeValueKey]);

            if (in_array($attributeKey, $this->getFilteredAttributeList())) {
                continue;
            }

            if ($attributeKey !== '') {
                $isMainAttribute = in_array($attributeKey, $this->getAttributeList());
                $isPdpAttribute = in_array($attributeKey, $this->getPdpAttributeList());
                $isAffiliateAttribute = in_array($attributeKey, $this->getAffiliateAttributeList());

                if ($isMainAttribute) {
                    $attributes[$attributeKey] = is_bool($attributeValue) ? (bool) $attributeValue : $attributeValue;
                }

                if ($isPdpAttribute) {
                    $pdpAttributes[$attributeKey] = $attributeValue;
                }

                if ($isAffiliateAttribute) {
                    $affiliateAttributes[$attributeKey] = $attributeValue;
                }

                if (!$isMainAttribute && !$isPdpAttribute && !$isAffiliateAttribute) {
                    $hiddenAttributes[$attributeKey] = $attributeValue;
                }

                if (strpos($attributeKey, static::KEY_IS_SELLABLE_PATTERN) === 0) {
                    $hiddenAttributes[$attributeKey] = (bool)$attributeValue;
                }
            }
        }

        $extraAttributesListKey = $this->getExtraAttributesListKeys();
        foreach ($extraAttributesListKey as $key) {
            $pdpAttributes[$key] = $dataSet[$key];
        }

        $dataSet[static::KEY_ATTRIBUTES] = $attributes;
        $dataSet[static::KEY_HIDDEN_ATTRIBUTES] = $hiddenAttributes;
        $dataSet[static::KEY_AFFILIATE_ATTRIBUTES] = $affiliateAttributes;
        $dataSet[static::KEY_PDP_ATTRIBUTES] = $pdpAttributes;
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
            'ean',
            'color',
            'size',
            'gtin',
            'benefit_store',
            'shopping_point_store',
            'brand',
        ];
    }

    /**
     * @return string[]
     */
    public function getPdpAttributeList(): array
    {
        return [
            'ean',
            'color',
            'size',
            'gtin',
            'brand',
        ];
    }

    /**
     * @return string[]
     */
    public function getFilteredAttributeList(): array
    {
        return [
            'customer_group_1',
            'customer_group_2',
            'customer_group_3',
            'customer_group_4',
            'customer_group_5',
            'purchase_price',
            'strike_price',
            'regular_sales_price',
            'benefit_store_sales_price',
            'benefit_amount',
        ];
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
