<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentNavigationWidget\Reader;

interface CategoryReaderInterface
{
    /**
     * @param int $idCategory
     *
     * @return bool
     */
    public function getIsCatalogVisible(int $idCategory): bool;
}
