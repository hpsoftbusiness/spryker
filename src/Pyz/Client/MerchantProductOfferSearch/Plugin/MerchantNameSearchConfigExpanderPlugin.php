<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MerchantProductOfferSearch\Plugin;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\MerchantProductOfferSearch\Plugin\MerchantNameSearchConfigExpanderPlugin as SprykerMerchantNameSearchConfigExpanderPlugin;

class MerchantNameSearchConfigExpanderPlugin extends SprykerMerchantNameSearchConfigExpanderPlugin
{
    protected const FILTER_MAX_COUNT_KEY = 'size';
    protected const FILTER_MAX_COUNT_VALUE = 1000;

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected function createMerchantNameFacetConfig(): FacetConfigTransfer
    {
        return parent::createMerchantNameFacetConfig()->setAggregationParams(
            [static::FILTER_MAX_COUNT_KEY => static::FILTER_MAX_COUNT_VALUE]
        );
    }
}
