<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\GoogleAnalyticWidget;

use Pyz\Shared\GoogleAnalytic\GoogleAnalyticConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class GoogleAnalyticWidgetConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getWebPropertyId(): string
    {
        return $this->get(GoogleAnalyticConstants::WEB_PROPERTY_ID);
    }
}
