<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Application;

use Pyz\Shared\Application\ApplicationConstants;
use Spryker\Yves\Application\ApplicationConfig as SprykerApplicationConfig;

/**
 * @method \Spryker\Shared\Application\ApplicationConfig getSharedConfig()
 */
class ApplicationConfig extends SprykerApplicationConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getWebPropertyId(): string
    {
        return $this->get(ApplicationConstants::WEB_PROPERTY_ID);
    }
}
