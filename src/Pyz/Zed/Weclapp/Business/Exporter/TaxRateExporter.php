<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;

class TaxRateExporter implements TaxRateExporterInterface
{
    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Spryker\Zed\Tax\Business\TaxFacadeInterface $taxFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        TaxFacadeInterface $taxFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @return void
     */
    public function exportAllTaxRates(): void
    {
        $taxRatesCollection = $this->taxFacade->getTaxRates();
        foreach ($taxRatesCollection->getTaxRates() as $taxRateTransfer) {
            $this->exportTaxRate($taxRateTransfer);
        }
    }

    /**
     * @param array $taxRatesIds
     *
     * @return void
     */
    public function exportTaxRates(array $taxRatesIds): void
    {
        foreach ($taxRatesIds as $taxRateId) {
            $taxRateTransfer = $this->taxFacade->getTaxRate($taxRateId);
            $this->exportTaxRate($taxRateTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    protected function exportTaxRate(TaxRateTransfer $taxRateTransfer): WeclappTaxTransfer
    {
        $weclappTaxTransfer = $this->weclappClient->getTax($taxRateTransfer);
        if (!$weclappTaxTransfer) {
            $weclappTaxTransfer = $this->weclappClient->postTax($taxRateTransfer);
        }

        if ($taxRateTransfer->getIdTaxRate()
            && $weclappTaxTransfer->getIdOrFail() != $taxRateTransfer->getIdWeclappTax()
        ) {
            $taxRateTransfer->setIdWeclappTax($weclappTaxTransfer->getId());
            $this->taxFacade->updateTaxRate($taxRateTransfer);
        }

        return $weclappTaxTransfer;
    }
}
