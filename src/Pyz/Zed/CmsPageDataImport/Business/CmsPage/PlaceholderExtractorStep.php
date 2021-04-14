<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsPageDataImport\Business\CmsPage;

use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPage\PlaceholderExtractorStep as SprykerPlaceholderExtractorStep;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PlaceholderExtractorStep extends SprykerPlaceholderExtractorStep
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

        $localizedPlaceholder = [];
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $placeholder = [];

            foreach ($this->placeholderNames as $placeholderName) {
                $placeholderValue = $dataSet[$placeholderName . '.' . $localeName] ?? null;
                if ($placeholderValue === null) {
                    $placeholder = null;

                    break;
                }

                $key = str_replace('placeholder.', '', $placeholderName);
                $placeholder[$key] = $placeholderValue;
            }

            $localizedPlaceholder[$idLocale] = $placeholder;
        }

        $localizedPlaceholder = $this->overrideMissingLocalizedPlaceholders($localizedPlaceholder, $defaultLocaleId);
        $dataSet[CmsPageDataSet::KEY_PLACEHOLDER] = $localizedPlaceholder;
    }

    /**
     * @param array $localizedPlaceholders
     * @param int $defaultLocaleId
     *
     * @return array
     */
    private function overrideMissingLocalizedPlaceholders(array $localizedPlaceholders, int $defaultLocaleId): array
    {
        foreach ($localizedPlaceholders as $idLocale => $placeholder) {
            if ($placeholder === null) {
                $localizedPlaceholders[$idLocale] = $localizedPlaceholders[$defaultLocaleId];
            }
        }

        return $localizedPlaceholders;
    }
}
