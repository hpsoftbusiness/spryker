<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute;

use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductManagementLocalizedAttributesExtractorStep implements DataImportStepInterface
{
    public const KEY_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $localizedAttributes = [];
        $defaultLocaleName = $dataSet[AddLocalesStep::KEY_DEFAULT_LOCALE_NAME];
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $values = $this->toArray($dataSet['values']);
            $valueTranslations = $this->getValueTranslations($dataSet, $localeName, $defaultLocaleName);

            $attributes = [
                'key_translation' => $this->getKeyTranslation($dataSet, $localeName, $defaultLocaleName),
                'values' => $values,
                'value_translations' => $valueTranslations,
            ];

            $localizedAttributes[$idLocale] = $attributes;
        }

        $dataSet[static::KEY_LOCALIZED_ATTRIBUTES] = $localizedAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     * @param string $defaultLocaleName
     *
     * @return array|string
     */
    private function getValueTranslations(DataSetInterface $dataSet, string $localeName, string $defaultLocaleName)
    {
        $valueTranslations = $dataSet['value_translations.' . $localeName] ?? $dataSet['value_translations.' . $defaultLocaleName] ?? [];
        if (!empty($valueTranslations)) {
            $valueTranslations = $this->toArray($valueTranslations);
            $valueTranslations = array_combine($this->toArray($dataSet['values']), $valueTranslations);
        }

        return $valueTranslations;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     * @param string $defaultLocaleName
     *
     * @return string
     */
    private function getKeyTranslation(DataSetInterface $dataSet, string $localeName, string $defaultLocaleName): string
    {
        return $dataSet['key_translation.' . $localeName] ?? $dataSet['key_translation.' . $defaultLocaleName];
    }

    /**
     * @param string $data
     *
     * @return array
     */
    private function toArray($data)
    {
        return array_map('trim', explode(',', $data));
    }
}
