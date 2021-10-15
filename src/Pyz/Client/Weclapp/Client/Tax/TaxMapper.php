<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Tax;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;

class TaxMapper implements TaxMapperInterface
{
    protected const TAX_TYPE = 'VALUE_ADDED_TAX';

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     * @param \Generated\Shared\Transfer\WeclappTaxTransfer|null $weclappTaxTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function mapTaxRateToWeclappTax(
        TaxRateTransfer $taxRateTransfer,
        ?WeclappTaxTransfer $weclappTaxTransfer = null
    ): WeclappTaxTransfer {
        if (!$weclappTaxTransfer) {
            $weclappTaxTransfer = new WeclappTaxTransfer();
        }

        $weclappTaxTransfer->setName($taxRateTransfer->getNameOrFail())
            ->setTaxValue((string)$taxRateTransfer->getRateOrFail())
            ->setTaxType(static::TAX_TYPE);

        return $weclappTaxTransfer;
    }

    /**
     * @param array $weclappTaxData
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function mapWeclappDataToWeclappTax(array $weclappTaxData): WeclappTaxTransfer
    {
        return (new WeclappTaxTransfer())->fromArray($weclappTaxData, true);
    }
}
