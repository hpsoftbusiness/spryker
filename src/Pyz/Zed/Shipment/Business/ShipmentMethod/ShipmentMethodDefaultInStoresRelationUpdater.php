<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentMethodDefaultInStoresRelationUpdater implements ShipmentMethodDefaultInStoresRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Pyz\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Pyz\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeRelationTransfer) {
            $this->executeUpdateStoreRelationTransaction($storeRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function executeUpdateStoreRelationTransaction(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $idShipmentMethod = $storeRelationTransfer->getIdEntity();

        $notAvailableInStores = $this->getNotIdStoresByIdShipmentMethod($idShipmentMethod);
        $this->entityManager->removeObsoleteDefaultRelationsByShipmenMethodId($idShipmentMethod, $notAvailableInStores);
        $this->entityManager->removeDefaultMethodsRelationsInStores($requestedIdStores);
        $this->entityManager->cleanDefaultMethodRelationsByShipmentMethodId($idShipmentMethod, $requestedIdStores);
        $this->entityManager->addDefaultMethodRelationInStores($requestedIdStores, $idShipmentMethod);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return int[]
     */
    protected function getNotIdStoresByIdShipmentMethod(int $idShipmentMethod): array
    {
        $defaultInStores = $this->shipmentRepository->getDefaultInStoresRelationByIdShipmentMethod($idShipmentMethod)->getIdStores();
        $availableInStores = $this->shipmentRepository->getStoreRelationByIdShipmentMethod($idShipmentMethod)->getIdStores();

        return array_diff($defaultInStores, $availableInStores);
    }
}
