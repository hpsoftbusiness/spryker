<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\LanguageSwitcherWidget;

use Pyz\Shared\Locale\LocaleConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class LanguageSwitcherWidgetConfig extends AbstractBundleConfig
{
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
