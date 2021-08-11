<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\LanguageSwitcherWidget\Widget;

use SprykerShop\Yves\LanguageSwitcherWidget\Widget\LanguageSwitcherWidget as SprykerLanguageSwitcherWidget;

/**
 * @method \Pyz\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetConfig getConfig()
 */
class LanguageSwitcherWidget extends SprykerLanguageSwitcherWidget
{
    /**
     * @param string $pathInfo
     * @param string $queryString
     * @param string $requestUri
     *
     * @return string[]
     */
    protected function getLanguages(string $pathInfo, $queryString, string $requestUri): array
    {
        $currentUrlStorage = $this->getFactory()
            ->getUrlStorageClient()
            ->findUrlStorageTransferByUrl($pathInfo);

        $localeUrls = [];

        if ($currentUrlStorage !== null && $currentUrlStorage->getLocaleUrls()->count() !== 0) {
            $localeUrls = (array)$currentUrlStorage->getLocaleUrls();
        }
        $locales = $this->getConfig()->getLocalsByStore($this->getFactory()->getStore()->getStoreName());

        if (!empty($localeUrls)) {
            return $this->attachLocaleUrlsFromStorageToLanguages($locales, $localeUrls, $queryString);
        }

        return $this->attachLocaleUrlsToLanguages($locales, $requestUri);
    }
}
