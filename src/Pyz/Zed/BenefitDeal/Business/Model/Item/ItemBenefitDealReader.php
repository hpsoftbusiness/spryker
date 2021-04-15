<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model\Item;

use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface;

class ItemBenefitDealReader implements ItemBenefitDealReaderInterface
{
    /**
     * @var \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface
     */
    private $repository;

    /**
     * @var \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemBenefitDealHydratorPluginInterface[]
     */
    private $hydratorPlugins;

    /**
     * @param \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface $repository
     * @param \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemBenefitDealHydratorPluginInterface[] $hydratorPlugins
     */
    public function __construct(BenefitDealRepositoryInterface $repository, array $hydratorPlugins)
    {
        $this->repository = $repository;
        $this->hydratorPlugins = $hydratorPlugins;
        $this->mapHydrators();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function hydrateOrderItemsWithBenefitDeals(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemBenefitDealEntityTransfers = $this->repository->findSalesOrderItemBenefitDealsByIdSalesOrderItem(
                $itemTransfer->getIdSalesOrderItem()
            );
            if (!empty($itemBenefitDealEntityTransfers)) {
                $this->hydrateOrderItem($itemTransfer, $itemBenefitDealEntityTransfers);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer[] $itemBenefitDealEntityTransfers
     *
     * @return void
     */
    private function hydrateOrderItem(ItemTransfer $itemTransfer, array $itemBenefitDealEntityTransfers): void
    {
        foreach ($itemBenefitDealEntityTransfers as $itemBenefitDealEntityTransfer) {
            if ($hydrator = $this->hydratorPlugins[$itemBenefitDealEntityTransfer->getType()] ?? null) {
                $hydrator->hydrateItem($itemTransfer, $itemBenefitDealEntityTransfer);
            }
        }
    }

    /**
     * @return void
     */
    private function mapHydrators(): void
    {
        $mappedHydrators = [];
        foreach ($this->hydratorPlugins as $hydratorPlugin) {
            $mappedHydrators[$hydratorPlugin->getType()] = $hydratorPlugin;
        }

        $this->hydratorPlugins = $mappedHydrators;
    }
}
