<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Application;

use Spryker\Shared\Application\ApplicationConstants as SprykerApplicationConstants;

interface ApplicationConstants extends SprykerApplicationConstants
{
    /**
     * Web application ID for Google Analytic
     *
     * @api
     */
    public const WEB_PROPERTY_ID = 'APPLICATION:WEB_PROPERTY_ID';
}
