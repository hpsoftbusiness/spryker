<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Country;

use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CountryConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCountryToLocaleRelations(): array
    {
        return $this->get(LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS, []);
    }

    /**
     * @return bool
     */
    public function isMultiCountryEnabled(): bool
    {
        return $this->get(CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED);
    }
}
