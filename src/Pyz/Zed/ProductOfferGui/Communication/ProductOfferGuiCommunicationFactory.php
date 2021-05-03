<?php

namespace Pyz\Zed\ProductOfferGui\Communication;

use Spryker\Zed\ProductOfferGui\Communication\ProductOfferGuiCommunicationFactory as SprykerProductOfferGuiCommunicationFactory;
use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable;
use Pyz\Zed\ProductOfferGui\Communication\Table\ProductOfferTable as SprykerProductOfferTable;

class ProductOfferGuiCommunicationFactory extends SprykerProductOfferGuiCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable
     */
    public function createProductOfferTable(): ProductOfferTable
    {
        return new SprykerProductOfferTable(
            $this->getProductOfferPropelQuery(),
            $this->getLocaleFacade(),
            $this->getProductOfferFacade(),
            $this->getRepository(),
            $this->getProductOfferTableExpanderPlugins()
        );
    }
}
