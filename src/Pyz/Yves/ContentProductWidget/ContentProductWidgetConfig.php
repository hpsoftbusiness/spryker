<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget;

use Pyz\Shared\Country\CountryConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ContentProductWidgetConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiCountryEnabled(): bool
    {
        return $this->get(CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED);
    }
}
