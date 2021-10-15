<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Tax\GetTax;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;
use Pyz\Client\Weclapp\Client\Tax\AbstractWeclappTaxClient;

class GetTaxClient extends AbstractWeclappTaxClient implements GetTaxClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/tax?name-eq=%s&taxValue-eq=%s';

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer|null
     */
    public function getTax(TaxRateTransfer $taxRateTransfer): ?WeclappTaxTransfer
    {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($taxRateTransfer)
        );
        $weclappTaxData = json_decode($weclappResponse->getBody()->__toString(), true)['result'][0]
            ?? null;
        if (!$weclappTaxData) {
            return null;
        }

        return $this->taxMapper->mapWeclappDataToWeclappTax($weclappTaxData);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return string
     */
    protected function getActionUrl(TaxRateTransfer $taxRateTransfer): string
    {
        return sprintf(
            static::ACTION_URL,
            $taxRateTransfer->getNameOrFail(),
            $taxRateTransfer->getRateOrFail()
        );
    }
}
