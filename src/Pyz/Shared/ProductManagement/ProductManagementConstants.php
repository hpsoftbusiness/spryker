<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\ProductManagement;

use Spryker\Shared\ProductManagement\ProductManagementConstants as SprykerProductManagementConstants;

interface ProductManagementConstants extends SprykerProductManagementConstants
{
    public const GROUP_ACTION_ACTIVATE = 'activate';
    public const GROUP_ACTION_DEACTIVATE = 'deactivate';
    public const GROUP_ACTION_DELETE = 'delete';
}
