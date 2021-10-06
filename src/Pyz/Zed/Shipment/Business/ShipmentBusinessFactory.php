<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business;

use Pyz\Zed\Shipment\Business\Expander\QuoteShipmentExpander;
use Pyz\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdater;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReader;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdater;
use Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpanderInterface;
use Spryker\Zed\Shipment\Business\ShipmentBusinessFactory as SprykerShipmentBusinessFactory;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdaterInterface;

/**
 * Class ShipmentBusinessFactory
 *
 * @package Pyz\Zed\Shipment\Business
 *
 * @method \Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 * @method \Pyz\Zed\Shipment\Persistence\ShipmentEntityManagerInterface getEntityManager()
 */
class ShipmentBusinessFactory extends SprykerShipmentBusinessFactory
{
    /**
     * @return \Pyz\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    public function createShipmentTaxCalculator()
    {
        return new ShipmentTaxRateCalculator(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface
     */
    public function createShipmentMethodReader(): ShipmentMethodReaderInterface
    {
        return new ShipmentMethodReader(
            $this->getRepository(),
            $this->getCurrencyFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpanderInterface
     */
    public function createQuoteShipmentExpander(): QuoteShipmentExpanderInterface
    {
        return new QuoteShipmentExpander(
            $this->getShipmentService(),
            $this->createMethodReader(),
            $this->createExpenseSanitizer(),
            $this->createShipmentMapper(),
            $this->getCalculationFacade(),
            $this->getShipmentGroupsSanitizerPlugins(),
            $this->createShipmentMethodReader()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdaterInterface
     */
    public function createShipmentMethodUpdater(): ShipmentMethodUpdaterInterface
    {
        return new ShipmentMethodUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createMethodPrice(),
            $this->createShipmentMethodStoreRelationUpdater(),
            $this->createShipmentMethodDefaultInStoresRelationUpdater()
        );
    }

    /**
     * @return \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface
     */
    public function createShipmentMethodDefaultInStoresRelationUpdater(): ShipmentMethodDefaultInStoresRelationUpdaterInterface
    {
        return new ShipmentMethodDefaultInStoresRelationUpdater(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
