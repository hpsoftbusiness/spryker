<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

use Pyz\Shared\Sso\SsoConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;

// ----------------------------------------------------------------------------
// --------------------------- Single Sigh On ---------------------------------
// ----------------------------------------------------------------------------

// >>> SSO

require 'config_sso-development.php';

/**
 * @todo replace with normal ENV var
 */
$config[SsoConstants::REDIRECT_URL] = 'https://www.de.myworld.cloud.spryker.toys/login_check';
