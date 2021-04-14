<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Router;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Router\RouterConfig as SprykerRouterConfig;

class RouterConfig extends SprykerRouterConfig
{
    /**
     * Overridden with entire list of mapped store languages to support static URLs with any supported language prefix.
     *
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        return array_keys(Store::getInstance()->getLocales());
    }
}
