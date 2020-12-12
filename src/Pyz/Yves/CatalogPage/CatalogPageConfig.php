<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage;

use SprykerShop\Yves\CatalogPage\CatalogPageConfig as SprykerShopCatalogPageConfig;

class CatalogPageConfig extends SprykerShopCatalogPageConfig
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isEmptyCategoryFilterValueVisible(): bool
    {
        return false;
    }
}
