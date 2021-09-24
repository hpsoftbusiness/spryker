<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Url\Persistence\UrlQueryContainer as SprykerUrlQueryContainer;

class UrlQueryContainer extends SprykerUrlQueryContainer implements UrlQueryContainerInterface
{
    /**
     * @param string $url
     * @param int $locale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryLocaleUrl(string $url, int $locale): SpyUrlQuery
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByUrl($url)->filterByFkLocale($locale);

        return $query;
    }
}
