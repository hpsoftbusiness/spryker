<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\TaxProductConnector\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\Business\Calculator\ProductItemTaxRateCalculator as SprykerProductItemTaxRateCalculator;

class ProductItemTaxRateCalculator extends SprykerProductItemTaxRateCalculator
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function recalculateWithItemTransfers(ArrayObject $itemTransfers): ArrayObject
    {
        $foundResults = $this->taxQueryContainer
            ->queryTaxSetByIdProductAbstractAndCountryIso2Codes(
                $this->getIdProductAbstruct($itemTransfers),
                $this->getCountryIso2Codes($itemTransfers)
            )
            ->find();

        $taxRatesByIdProductAbstractAndCountry = $this->mapByIdProductAbstractAndCountry($foundResults);

        foreach ($itemTransfers as $itemTransfer) {
            $taxRate = $this->getEffectiveTaxRate(
                $taxRatesByIdProductAbstractAndCountry,
                $itemTransfer->getIdProductAbstract(),
                $this->getShippingCountryIso2CodeByItem($itemTransfer)
            );
            $itemTransfer->setTaxRate($taxRate);

            $itemTransfer->setIdWeclappTax($this->getIdWeclappTax($foundResults, $itemTransfer));
        }

        return $itemTransfers;
    }

    /**
     * @param iterable $taxRates
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function getIdWeclappTax(iterable $taxRates, ItemTransfer $itemTransfer): ?string
    {
        foreach ($taxRates as $taxRate) {
            if ($taxRate[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT] == $itemTransfer->getIdProductAbstract()
                && $taxRate[TaxProductConnectorQueryContainer::COL_COUNTRY_CODE] == $this->getShippingCountryIso2CodeByItem($itemTransfer)
            ) {
                return $taxRate[TaxProductConnectorQueryContainer::COL_ID_WECLAPP_TAX];
            }
        }

        return null;
    }
}
