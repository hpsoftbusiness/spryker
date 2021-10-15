<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Tax\PostTax;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;

interface PostTaxClientInterface
{
    /**
     * Post tax
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function postTax(TaxRateTransfer $taxRateTransfer): WeclappTaxTransfer;
}
