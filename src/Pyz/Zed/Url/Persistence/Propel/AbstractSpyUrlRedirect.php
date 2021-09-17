<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Url\Persistence\Propel;

use Spryker\Zed\Url\Persistence\Propel\AbstractSpyUrlRedirect as SprykerAbstractSpyUrlRedirect;

abstract class AbstractSpyUrlRedirect extends SprykerAbstractSpyUrlRedirect
{
    /**
     * For multi store architecture we allow have the same url in DB.
     * Unique key was changed from (url) to (locale + url)
     *
     * @return void
     */
    protected function assertRedirectLoops()
    {
//        foreach ($this->getSpyUrls() as $urlEntity) {
//            if ($urlEntity->getUrl() === $this->getToUrl()) {
//                throw new RedirectLoopException(sprintf(
//                    'Redirecting "%s" to "%s" resolves in a URL redirect loop.',
//                    $urlEntity->getUrl(),
//                    $this->getToUrl()
//                ));
//            }
//        }
    }
}
