<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\TaxProductConnector\Persistence;

use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer as SprykerTaxProductConnectorQueryContainer;

class TaxProductConnectorQueryContainer extends SprykerTaxProductConnectorQueryContainer
{
    public const COL_ID_WECLAPP_TAX = 'IdWeclappTax';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module Country
     *
     * @deprecated Use {@link queryTaxSetByIdProductAbstractAndCountryIso2Codes()} instead.
     *
     * @param int[] $allIdProductAbstracts
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountryIso2Code(array $allIdProductAbstracts, $countryIso2Code)
    {
        $spyTaxSetQuery = parent::queryTaxSetByIdProductAbstractAndCountryIso2Code($allIdProductAbstracts, $countryIso2Code);

        return $this->addQueryIdWeclappTax($spyTaxSetQuery);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module Country
     *
     * @param int[] $idProductAbstracts
     * @param string[] $countryIso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountryIso2Codes(array $idProductAbstracts, array $countryIso2Code): SpyTaxSetQuery
    {
        $spyTaxSetQuery = parent::queryTaxSetByIdProductAbstractAndCountryIso2Codes($idProductAbstracts, $countryIso2Code);

        return $this->addQueryIdWeclappTax($spyTaxSetQuery);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetQuery $spyTaxSetQuery
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    protected function addQueryIdWeclappTax(SpyTaxSetQuery $spyTaxSetQuery): SpyTaxSetQuery
    {
        return $spyTaxSetQuery->useSpyTaxSetTaxQuery()
            ->useSpyTaxRateQuery()
                ->withColumn(SpyTaxRateTableMap::COL_ID_WECLAPP_TAX, static::COL_ID_WECLAPP_TAX)
                ->groupBy(SpyTaxRateTableMap::COL_ID_WECLAPP_TAX)
                ->endUse()
            ->endUse();
    }
}
