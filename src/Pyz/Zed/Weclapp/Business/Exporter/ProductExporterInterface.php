<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

interface ProductExporterInterface
{
    /**
     * @param array $productsIds
     *
     * @return void
     */
    public function exportProducts(array $productsIds): void;

    /**
     * @return void
     */
    public function exportAllProducts(): void;
}
