<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

interface CategoryExporterInterface
{
    /**
     * @return void
     */
    public function exportAllCategories(): void;

    /**
     * @param array $categoriesIds
     *
     * @return void
     */
    public function exportCategories(array $categoriesIds): void;
}
