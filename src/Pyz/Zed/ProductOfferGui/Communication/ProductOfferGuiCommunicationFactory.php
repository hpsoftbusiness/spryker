<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferGui\Communication;

use Pyz\Zed\ProductOfferGui\Communication\Table\ProductOfferTable as SprykerProductOfferTable;
use Spryker\Zed\ProductOfferGui\Communication\ProductOfferGuiCommunicationFactory as SprykerProductOfferGuiCommunicationFactory;
use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable;

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
