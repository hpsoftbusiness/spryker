<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ShipmentMethodForm;
use Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ViewShipmentMethodForm;
use Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethodFormDataProvider;
use Pyz\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable as TableShipmentMethodTable;
use Pyz\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentMethodFormDataProvider as SpyShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory as SprykerShipmentGuiCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable;
use Spryker\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs as BaseShipmentMethodTabs;
use Symfony\Component\Form\FormInterface;

class ShipmentGuiCommunicationFactory extends SprykerShipmentGuiCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createViewShipmentMethodForm(?ShipmentMethodTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ViewShipmentMethodForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentMethodForm(ShipmentMethodTransfer $data, $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShipmentMethodForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs
     */
    public function createShipmentMethodTabs(): BaseShipmentMethodTabs
    {
        return new ShipmentMethodTabs();
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable
     */
    public function createShipmentMethodTable(): ShipmentMethodTable
    {
        return new TableShipmentMethodTable($this->getShipmentMethodQuery());
    }

    /**
     * @return \Pyz\Zed\ShipmentGui\Communication\Form\ShipmentMethodFormDataProvider
     */
    public function createShipmentMethodFormDataProvider(): SpyShipmentMethodFormDataProvider
    {
        return new ShipmentMethodFormDataProvider(
            $this->getShipmentFacade(),
            $this->getTaxFacade()
        );
    }
}
