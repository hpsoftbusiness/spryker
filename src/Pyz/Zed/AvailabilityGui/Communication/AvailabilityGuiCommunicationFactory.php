<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui\Communication;

use Pyz\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable;
use Pyz\Zed\AvailabilityGui\Communication\Table\AvailabilityTable;
use Spryker\Zed\AvailabilityGui\Communication\AvailabilityGuiCommunicationFactory as SprykerAvailabilityGuiCommunicationFactory;

class AvailabilityGuiCommunicationFactory extends SprykerAvailabilityGuiCommunicationFactory
{
    /**
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Pyz\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable
     */
    public function createAvailabilityAbstractTable($idLocale, $idStore): AvailabilityAbstractTable
    {
        return new AvailabilityAbstractTable(
            $this->createProductAvailabilityHelper(),
            $this->getStoreFacade(),
            $idStore,
            $idLocale,
            $this->getRepository()
        );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Pyz\Zed\AvailabilityGui\Communication\Table\AvailabilityTable
     */
    public function createAvailabilityTable($idProductAbstract, $idLocale, $idStore): AvailabilityTable
    {
        return new AvailabilityTable(
            $this->createProductAvailabilityHelper(),
            $this->getStoreFacade(),
            $idProductAbstract,
            $idLocale,
            $idStore
        );
    }
}
