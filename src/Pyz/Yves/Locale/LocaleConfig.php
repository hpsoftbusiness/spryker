<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale;

use Pyz\Shared\Locale\LocaleConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class LocaleConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCountryToLocaleRelations(): array
    {
        return $this->get(LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS, []);
    }
}
