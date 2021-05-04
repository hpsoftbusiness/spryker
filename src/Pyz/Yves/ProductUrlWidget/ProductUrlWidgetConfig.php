<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\ProductUrlWidget;

use Pyz\Shared\Country\CountryConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductUrlWidgetConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiCountryFeatureEnabled(): bool
    {
        return $this->get(CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED, false);
    }
}

