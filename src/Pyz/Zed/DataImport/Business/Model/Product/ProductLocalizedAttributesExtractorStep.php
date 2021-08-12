<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Product;

use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductLocalizedAttributesExtractorStep implements DataImportStepInterface
{
    public const KEY_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    protected const KEY_ABSTRACT_SKU = 'abstract_sku';
    protected const KEY_CONCRETE_SKU = 'concrete_sku';

    protected const DEFAULT_VALUE = '';
    protected const AFFILIATE_PRODUCT_ATTRIBUTE_KEY = 'product.value_73';
    protected const AFFILIATE_PRODUCT_ATTRIBUTE_VALUE = 'TRUE';

    protected const KEY_PRODUCT_NAME = 'product.name';
    protected const KEY_PRODUCT_DESCRIPTION = 'product.description';
    protected const KEY_PRODUCT_META_TITLE = 'product_abstract.meta_title';
    protected const KEY_PRODUCT_META_DESCRIPTION = 'product_abstract.meta_description';
    protected const KEY_PRODUCT_META_KEYWORDS = 'product_abstract.meta_keywords';

    protected const LOCALIZED_ATTRIBUTE_MAP = [
        self::KEY_PRODUCT_NAME => 'Name',
        self::KEY_PRODUCT_DESCRIPTION => 'Description',
        self::KEY_PRODUCT_META_TITLE => 'MetaTitle',
        self::KEY_PRODUCT_META_DESCRIPTION => 'MetaDescription',
        self::KEY_PRODUCT_META_KEYWORDS => 'MetaKeywords',
    ];

    /**
     * @var array
     */
    protected $defaultAttributes = [];

    /**
     * @var bool
     */
    private $isAbstract;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     * @param array $defaultAttributes
     * @param bool $isAbstract
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        array $defaultAttributes = [],
        bool $isAbstract = true
    ) {
        $this->defaultAttributes = $defaultAttributes;
        $this->isAbstract = $isAbstract;
        $this->productRepository = $productRepository;
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
        $sku = $this->getSkuForLocalize($dataSet);

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
                $defaultAttributeValue = $dataSet[$defaultAttribute . '.' . $localeName] ?? static::DEFAULT_VALUE;
                if ($defaultAttribute === self::KEY_PRODUCT_NAME) {
                    $defaultAttributeValue = $dataSet[$defaultAttribute . '.' . $localeName] ?? $this->sku;
                }
//                if ($defaultAttributeValue === null) {
//                    $localizedAttributes[$idLocale] = null;
//
//                    break;
//                }

                $localizedAttributes[$idLocale][$defaultAttribute] = $defaultAttributeValue;
//                $keysToUnset[] = $defaultAttribute.'.'.$localeName;
            }
        }

        foreach ($keysToUnset as $key) {
            unset($dataSet[$key]);
        }

        $existLocalizedAttributes = $this->getExistLocalizedAbstractAttributes($sku);

        $localizedAttributes = $this->overrideMissingLocalizedAttributes(
            $localizedAttributes,
            $existLocalizedAttributes
        );
        $dataSet[static::KEY_LOCALIZED_ATTRIBUTES] = $localizedAttributes;
    }

    /**
     * @param array $localizedAttributes
     * @param array $existLocalizedAttributes
     *
     * @return array
     */
    protected function overrideMissingLocalizedAttributes(
        array $localizedAttributes,
        array $existLocalizedAttributes
    ): array {
        foreach ($localizedAttributes as $idLocale => $attributes) {
            if (isset($existLocalizedAttributes[$idLocale])) {
                foreach ($localizedAttributes[$idLocale] as $key => $value) {
                    $localizedAttributes[$idLocale][$key] = $this->getLocalizedAttributeFromExist(
                        $existLocalizedAttributes[$idLocale],
                        $key,
                        $value
                    );
                }
            }
        }

        return $localizedAttributes;
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

    /**
     * @param string $sku
     *
     * @return array
     */
    protected function getExistLocalizedAbstractAttributes(string $sku): array
    {
        return $this->productRepository->getProductAbstractLocalizedAttributesBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    protected function getExistLocalizedConcreteAttributes(string $sku): array
    {
        return $this->productRepository->getProductConcreteLocalizedAttributesBySku($sku);
    }

    /**
     * @param array $existLocalizedAttribute
     * @param string $key
     * @param string|array $value
     *
     * @return string|array
     */
    private function getLocalizedAttributeFromExist(array $existLocalizedAttribute, string $key, $value)
    {
        if (!isset(static::LOCALIZED_ATTRIBUTE_MAP[$key]) || is_array($value)) {
            return $value;
        }
        $existedLocalizedValues = $existLocalizedAttribute[static::LOCALIZED_ATTRIBUTE_MAP[$key]] ?? static::DEFAULT_VALUE;

        if ($value !== static::DEFAULT_VALUE && $value !== $existedLocalizedValues) {
            return $value;
        }
        if ($value === static::DEFAULT_VALUE && $key === self::KEY_PRODUCT_NAME &&
            ($existLocalizedAttribute[static::LOCALIZED_ATTRIBUTE_MAP[$key]] === $this->sku ||
                $existLocalizedAttribute[static::LOCALIZED_ATTRIBUTE_MAP[$key]] === static::DEFAULT_VALUE)) {
            return $this->sku;
        }

        if ($existedLocalizedValues === static::DEFAULT_VALUE && $key === self::KEY_PRODUCT_NAME) {
            return $this->sku;
        }

        return $existedLocalizedValues;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    private function getSkuForLocalize(DataSetInterface $dataSet): string
    {
        $sku = ($this->isAbstract) ? $dataSet[static::KEY_ABSTRACT_SKU] : $dataSet[static::KEY_CONCRETE_SKU];
        $this->sku = $sku;

        return $sku;
    }
}
