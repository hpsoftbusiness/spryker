<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\DataImportStep;

use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep as SprykerLocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class LocalizedAttributesExtractorStep extends SprykerLocalizedAttributesExtractorStep
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $defaultLocaleId = $dataSet[AddLocalesStep::KEY_DEFAULT_LOCALE_ID] ?? null;
        if (!$defaultLocaleId) {
            parent::execute($dataSet);

            return;
        }

        $localizedAttributes = [];
        $nonMappedLocaleIds = [];
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $attributes = $this->getLocalizedAttributes($dataSet, $localeName);
            if ($attributes === null) {
                $nonMappedLocaleIds[] = $idLocale;

                continue;
            }

            $localizedAttributes[$idLocale] = $attributes;
        }

        $inheritedLocalizedAttributes = $this->inheritMissingLocalizedAttributes($localizedAttributes[$defaultLocaleId], $nonMappedLocaleIds);

        $dataSet[static::KEY_LOCALIZED_ATTRIBUTES] = $localizedAttributes + $inheritedLocalizedAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     *
     * @return array|null
     */
    protected function getLocalizedAttributes(DataSetInterface $dataSet, string $localeName): ?array
    {
        $attributes = [];
        foreach ($this->attributeNames as $attributeName) {
            $attributes[$attributeName] = $dataSet[$attributeName . '.' . $localeName] ?? null;
            if ($attributes[$attributeName] === null) {
                $attributes = null;

                break;
            }
        }

        return $attributes;
    }

    /**
     * @param array $defaultLocalizedAttributes
     * @param int[] $nonMappedLocaleIds
     *
     * @return array
     */
    protected function inheritMissingLocalizedAttributes(array $defaultLocalizedAttributes, array $nonMappedLocaleIds): array
    {
        return array_fill_keys($nonMappedLocaleIds, $defaultLocalizedAttributes);
    }
}
