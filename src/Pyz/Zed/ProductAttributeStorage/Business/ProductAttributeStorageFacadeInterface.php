<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Business;

interface ProductAttributeStorageFacadeInterface
{
    /**
     * @return void
     */
    public function publishProductManagementAttributeVisibility(): void;
}
