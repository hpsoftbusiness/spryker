<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\TaxProductConnector\Business\Model;

use ArrayObject;
use Pyz\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator as SprykerProductItemTaxRateCalculator;

class ProductItemTaxRateCalculator extends SprykerProductItemTaxRateCalculator
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $taxRates
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function setItemsTax(ArrayObject $itemTransfers, array $taxRates): ArrayObject
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setTaxRate($this->getEffectiveTaxRate($taxRates, $itemTransfer->getIdProductAbstract()));
            $itemTransfer->setIdWeclappTax($this->getIdWeclappTax($taxRates, $itemTransfer->getIdProductAbstract()));
        }

        return $itemTransfers;
    }

    /**
     * @param array $taxRates
     * @param int|null $idProductAbstract
     *
     * @return string|null
     */
    protected function getIdWeclappTax(array $taxRates, ?int $idProductAbstract): ?string
    {
        foreach ($taxRates as $taxRate) {
            if ($taxRate[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT] == $idProductAbstract) {
                return $taxRate[TaxProductConnectorQueryContainer::COL_ID_WECLAPP_TAX];
            }
        }

        return null;
    }
}
