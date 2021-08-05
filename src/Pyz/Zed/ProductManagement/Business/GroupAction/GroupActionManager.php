<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business\GroupAction;

use Generated\Shared\Transfer\ProductGroupActionTransfer;
use Pyz\Shared\ProductManagement\ProductManagementConstants;
use Pyz\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class GroupActionManager implements GroupActionManagerInterface
{
    use TransactionTrait;

    /**
     * @var \Pyz\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Pyz\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     */
    public function __construct(ProductManagementToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    public function groupAction(ProductGroupActionTransfer $groupActionTransfer): void
    {
        switch ($groupActionTransfer->getAction()) {
            case ProductManagementConstants::GROUP_ACTION_ACTIVATE:
                $this->groupActionActivate($groupActionTransfer);

                return;
            case ProductManagementConstants::GROUP_ACTION_DEACTIVATE:
                $this->groupActionDeactivate($groupActionTransfer);

                return;
            case ProductManagementConstants::GROUP_ACTION_DELETE:
                $this->groupActionDelete($groupActionTransfer);

                return;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    protected function groupActionActivate(ProductGroupActionTransfer $groupActionTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($groupActionTransfer) {
                $idsProductsConcrete = $this->productFacade
                    ->findProductConcreteIdsByAbstractProductIds($groupActionTransfer->getIds());
                foreach ($idsProductsConcrete as $idProductConcrete) {
                    $this->productFacade->activateProductConcrete($idProductConcrete);
                }
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    protected function groupActionDeactivate(ProductGroupActionTransfer $groupActionTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($groupActionTransfer) {
                $idsProductsConcrete = $this->productFacade
                    ->findProductConcreteIdsByAbstractProductIds($groupActionTransfer->getIds());
                foreach ($idsProductsConcrete as $idProductConcrete) {
                    $this->productFacade->deactivateProductConcrete($idProductConcrete);
                }
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    protected function groupActionDelete(ProductGroupActionTransfer $groupActionTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($groupActionTransfer) {
                foreach ($groupActionTransfer->getIds() as $idProductAbstract) {
                    $this->productFacade->markProductAsRemoved($idProductAbstract);
                }
            }
        );
    }
}
