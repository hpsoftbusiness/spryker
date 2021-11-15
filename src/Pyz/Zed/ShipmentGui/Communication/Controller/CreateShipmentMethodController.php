<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ShipmentGui\Communication\Controller\CreateShipmentMethodController as SprykerCreateShipmentMethodController;
use Symfony\Component\HttpFoundation\Request;

class CreateShipmentMethodController extends SprykerCreateShipmentMethodController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $shipmentMethodTabs = $this->getFactory()->createShipmentMethodTabs();
        $dataProvider = $this->getFactory()->createShipmentMethodFormDataProvider();
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setStoreRelation(new StoreRelationTransfer())
            ->setDefaultInStoresRelation(new StoreRelationTransfer());
        $shipmentMethodForm = $this->getFactory()->createShipmentMethodForm(
            $dataProvider->getData($shipmentMethodTransfer),
            $dataProvider->getOptions()
        );
        $shipmentMethodForm->handleRequest($request);

        if ($shipmentMethodForm->isSubmitted() && $shipmentMethodForm->isValid()) {
            return $this->handleShipmentMethodForm($shipmentMethodForm);
        }

        return $this->viewResponse([
            'shipmentMethodTabs' => $shipmentMethodTabs->createView(),
            'shipmentMethodForm' => $shipmentMethodForm->createView(),
        ]);
    }
}
