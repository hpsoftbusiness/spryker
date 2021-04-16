<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AffiliateDataStep implements DataImportStepInterface
{
    public const AFFILIATE_DATA_KEY = 'affiliate_data';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $affiliateAttributes = [];

        foreach ($dataSet as $key => $value) {
            if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+)$/', $key, $match)) {
                continue;
            }

            $attributeValueKey = $this->getAttributeValuePrefix() . $match[1];
            $attributeKey = trim(strtolower(str_replace(' ', '_', $value)));
            $attributeValue = trim($dataSet[$attributeValueKey]);

            if ($attributeKey !== '' && in_array($attributeKey, $this->getAffiliateAttributeList())) {
                $affiliateAttributes[$attributeKey] = $attributeValue;
            }
        }

        $dataSet[static::AFFILIATE_DATA_KEY] = $affiliateAttributes;
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
}
