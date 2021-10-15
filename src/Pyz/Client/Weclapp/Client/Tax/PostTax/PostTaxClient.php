<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Tax\PostTax;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;
use Pyz\Client\Weclapp\Client\Tax\AbstractWeclappTaxClient;

class PostTaxClient extends AbstractWeclappTaxClient implements PostTaxClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/tax';

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function postTax(TaxRateTransfer $taxRateTransfer): WeclappTaxTransfer
    {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($taxRateTransfer)
        );
        $weclappTaxData = json_decode($weclappResponse->getBody()->__toString(), true);

        return $this->taxMapper
            ->mapWeclappDataToWeclappTax($weclappTaxData);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return array
     */
    protected function getRequestBody(TaxRateTransfer $taxRateTransfer): array
    {
        return $this->taxMapper
            ->mapTaxRateToWeclappTax($taxRateTransfer)
            ->toArray(true, true);
    }
}
