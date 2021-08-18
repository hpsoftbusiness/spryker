<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Router;

use Pyz\Shared\Locale\LocaleConstants;
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

    /**
     * @return string
     */
    public function getStorePrefix(): string
    {
        return Store::getInstance()->getStorePrefix();
    }

    /**
     * @param string $store
     *
     * @return array
     */
    public function getLocalsByStore(string $store): array
    {
        $localsPerStore = $this->get(LocaleConstants::LOCALS_PER_STORES, []);

        return $localsPerStore[$store] ?? [];
    }
}
