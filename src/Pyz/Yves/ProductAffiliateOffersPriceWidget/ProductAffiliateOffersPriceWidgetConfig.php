<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget;

use Pyz\Shared\Country\CountryConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductAffiliateOffersPriceWidgetConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiCountryFeatureEnabled(): bool
    {
        return $this->get(CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED, false);
    }
}
