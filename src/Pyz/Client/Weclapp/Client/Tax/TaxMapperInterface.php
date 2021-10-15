<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Tax;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;

interface TaxMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     * @param \Generated\Shared\Transfer\WeclappTaxTransfer|null $weclappTaxTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function mapTaxRateToWeclappTax(
        TaxRateTransfer $taxRateTransfer,
        ?WeclappTaxTransfer $weclappTaxTransfer = null
    ): WeclappTaxTransfer;

    /**
     * @param array $weclappTaxData
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function mapWeclappDataToWeclappTax(array $weclappTaxData): WeclappTaxTransfer;
}
