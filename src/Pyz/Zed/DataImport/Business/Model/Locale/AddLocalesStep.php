<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Locale;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep as SprykerAddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddLocalesStep extends SprykerAddLocalesStep
{
    public const KEY_DEFAULT_LOCALE_ID = 'default_locale_id';
    public const KEY_DEFAULT_LOCALE_NAME = 'default_locale_name';

    /**
     * @var int
     */
    private $defaultLocaleId;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        parent::execute($dataSet);

        $dataSet[self::KEY_DEFAULT_LOCALE_NAME] = $this->getDefaultLocaleName();
        $dataSet[self::KEY_DEFAULT_LOCALE_ID] = $this->getDefaultLocaleId();
    }

    /**
     * @return int
     */
    private function getDefaultLocaleId(): int
    {
        if ($this->defaultLocaleId === null) {
            $defaultLocaleName = $this->getDefaultLocaleName();
            $this->defaultLocaleId = SpyLocaleQuery::create()
                ->findOneByLocaleName($defaultLocaleName)
                ->getIdLocale();
        }

        return $this->defaultLocaleId;
    }

    /**
     * @return string
     */
    private function getDefaultLocaleName(): string
    {
        return current($this->getLocales());
    }
}
