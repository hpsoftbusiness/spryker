<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

interface TaxRateExporterInterface
{
    /**
     * @return void
     */
    public function exportAllTaxRates(): void;

    /**
     * @param array $taxRatesIds
     *
     * @return void
     */
    public function exportTaxRates(array $taxRatesIds): void;
}
