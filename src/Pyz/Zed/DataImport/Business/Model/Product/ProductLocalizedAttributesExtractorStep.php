<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Product;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductLocalizedAttributesExtractorStep implements DataImportStepInterface
{
    public const KEY_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    protected const AFFILIATE_PRODUCT_ATTRIBUTE_KEY = 'product.value_73';
    protected const AFFILIATE_PRODUCT_ATTRIBUTE_VALUE = 'TRUE';

    /**
     * @var array
     */
    protected $defaultAttributes = [];

    /**
     * @param array $defaultAttributes
     */
    public function __construct(array $defaultAttributes = [])
    {
        $this->defaultAttributes = $defaultAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $keysToUnset = [];
        $localizedAttributes = [];
        foreach ($dataSet['locales'] as $localeName => $idLocale) {
            $attributes = [];
            foreach ($dataSet as $key => $value) {
                if (!preg_match('/^' . $this->getAttributeKeyPrefix() . '(\d+).' . $localeName . '$/', $key, $match)) {
                    continue;
                }

                $attributeValueKey = $this->getAttributeValuePrefix() . $match[1] . '.' . $localeName;
                $attributeKey = trim($value);
                $attributeValue = trim($dataSet[$attributeValueKey]);

                if ($attributeKey !== '') {
                    $attributes[$attributeKey] = $attributeValue;
                }

                $keysToUnset[] = $match[0];
                $keysToUnset[] = $attributeValueKey;
            }
//            Color and Size - Super attrs
//            foreach ($this->getMandatoryAttributes() as $attrKey => $dataSetDefaultValueKey) {
//                if (!isset($attributes[$attrKey]) && $dataSet[$dataSetDefaultValueKey] !== "" && !$this->isAfiliateProduct($dataSet)) {
//                    $attributes[$attrKey] = $this->getMandatoryAttributeValue(
//                        $attrKey,
//                        $dataSet[$dataSetDefaultValueKey]
//                    );
//                }
//            }

            $localizedAttributes[$idLocale] = [
                'attributes' => $attributes,
            ];

            foreach ($this->defaultAttributes as $defaultAttribute) {
                $localizedAttributes[$idLocale][$defaultAttribute] = $dataSet[$defaultAttribute . '.' . $localeName];

                $keysToUnset[] = $defaultAttribute . '.' . $localeName;
            }
        }

        foreach ($keysToUnset as $key) {
            unset($dataSet[$key]);
        }

        $dataSet[static::KEY_LOCALIZED_ATTRIBUTES] = $localizedAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isAfiliateProduct(DataSetInterface $dataSet): bool
    {
        return $dataSet[static::AFFILIATE_PRODUCT_ATTRIBUTE_KEY] === static::AFFILIATE_PRODUCT_ATTRIBUTE_VALUE;
    }

    /**
     * @return array
     */
    protected function getMandatoryAttributes(): array
    {
        return [
            'color' => 'product.value_05',
            'size' => 'product.value_06',
        ];
    }

    /**
     * @param string $mandatoryAttributeKey
     * @param string $initialMandatoryAttributeValue
     *
     * @return string
     */
    protected function getMandatoryAttributeValue(
        string $mandatoryAttributeKey,
        string $initialMandatoryAttributeValue
    ): string {
        $prepareAttributeValueFunction = $this->findPrepareMandatoryAttributeValueFunction($mandatoryAttributeKey);

        if (!$prepareAttributeValueFunction) {
            return $initialMandatoryAttributeValue;
        }

        return $prepareAttributeValueFunction($initialMandatoryAttributeValue);
    }

    /**
     * @param string $attributeKey
     *
     * @return callable|null
     */
    protected function findPrepareMandatoryAttributeValueFunction(string $attributeKey): ?callable
    {
        $attributePreparationFunctions = [
            'color' => function ($attributeValue): string {
                $attributeValue = trim($attributeValue);
                $firstLetterUppercased = mb_strtoupper(mb_substr($attributeValue, 0, 1));

                return $firstLetterUppercased . mb_substr($attributeValue, 1);
            },
            'size' => function ($attributeValue): string {
                return mb_strtoupper(trim($attributeValue));
            },
        ];

        return $attributePreparationFunctions[$attributeKey] ?? null;
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
}
