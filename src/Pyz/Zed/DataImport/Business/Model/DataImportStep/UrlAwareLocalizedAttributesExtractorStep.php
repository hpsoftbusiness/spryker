<?php declare(strict_types=1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\DataImportStep;

use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class UrlAwareLocalizedAttributesExtractorStep extends LocalizedAttributesExtractorStep
{
    protected const KEY_PATH = 'path';
    protected const KEY_HOST = 'host';
    protected const KEY_URL = 'url';

    /**
     * [ $localeId => $localeLanguageIsoCode, ...]
     *
     * @var array
     */
    private $locales;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @param array $attributeNames
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(array $attributeNames, Store $store)
    {
        parent::__construct($attributeNames);

        $this->store = $store;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($this->locales)) {
            $this->locales = $this->getLocaleIdToLanguageCollection($dataSet[AddLocalesStep::KEY_LOCALES]);
        }
        parent::execute($dataSet);
    }

    /**
     * @param array $defaultLocalizedAttributes
     * @param array $nonMappedLocaleIds
     *
     * @return array
     */
    protected function inheritMissingLocalizedAttributes(array $defaultLocalizedAttributes, array $nonMappedLocaleIds): array
    {
        $inheritedLocalizedAttributes = parent::inheritMissingLocalizedAttributes($defaultLocalizedAttributes, $nonMappedLocaleIds);
        foreach ($inheritedLocalizedAttributes as $idLocale => &$localizedAttribute) {
            $localizedAttribute[self::KEY_URL] = $this->buildLocalizedUrlByLocaleId(
                $localizedAttribute[self::KEY_URL],
                $idLocale
            );
        }

        return $inheritedLocalizedAttributes;
    }

    /**
     * @param string $url
     * @param int $idLocale
     *
     * @return string
     */
    private function buildLocalizedUrlByLocaleId(string $url, int $idLocale): string
    {
        $parsedUrl = parse_url(trim($url, '/'));
        $pathElements = explode('/', (string)$parsedUrl[self::KEY_PATH]);
        $pathElements[0] = $this->locales[$idLocale];
        $path = implode('/', $pathElements);

        return (string)($parsedUrl[self::KEY_HOST] ?? '') . '/' . $path;
    }

    /**
     * @param array $locales
     *
     * @return array
     */
    private function getLocaleIdToLanguageCollection(array $locales): array
    {
        $storeLocaleLanguages = array_flip($this->store->getLocales());

        return array_combine(
            $locales,
            array_map(
                static function (string $localeName) use ($storeLocaleLanguages) {
                    return $storeLocaleLanguages[$localeName];
                },
                array_keys($locales)
            )
        );
    }
}
