<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country;

use Pyz\Shared\Country\CountryConstants;
use Spryker\Zed\Country\CountryConfig as SprykerCountryConfig;

class CountryConfig extends SprykerCountryConfig
{
    /**
     * @return array
     */
    public function getAvailableCountiesIso2Code(): array
    {
        return $this->get(CountryConstants::CLUSTER_COUNTRIES, []);
    }
}
