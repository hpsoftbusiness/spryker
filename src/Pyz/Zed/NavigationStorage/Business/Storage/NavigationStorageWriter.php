<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeStorageTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriter as SprykerNavigationStorageWriter;
use Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToNavigationInterface;
use Spryker\Zed\NavigationStorage\Dependency\Service\NavigationStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface;

class NavigationStorageWriter extends SprykerNavigationStorageWriter
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\NavigationStorage\Dependency\Service\NavigationStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param \Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToNavigationInterface $navigationFacade
     * @param \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param bool $isSendingToQueue
     */
    public function __construct(
        NavigationStorageToUtilSanitizeServiceInterface $utilSanitizeService,
        NavigationStorageToNavigationInterface $navigationFacade,
        NavigationStorageQueryContainerInterface $queryContainer,
        Store $store,
        UtilEncodingServiceInterface $utilEncodingService,
        $isSendingToQueue
    ) {
        parent::__construct($utilSanitizeService, $navigationFacade, $queryContainer, $store, $isSendingToQueue);

        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeNodeTransfer[]|\ArrayObject $navigationTreeNodeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\NavigationNodeStorageTransfer[]
     */
    protected function mapToNavigationNodeStorageTransfer(ArrayObject $navigationTreeNodeTransfers): ArrayObject
    {
        $nodes = new ArrayObject();
        foreach ($navigationTreeNodeTransfers as $navigationTreeNodeTransfer) {
            $navigationNodeLocalizedAttributes = $navigationTreeNodeTransfer->getNavigationNode()->getNavigationNodeLocalizedAttributes()->getIterator()->current();
            if (!$navigationNodeLocalizedAttributes instanceof NavigationNodeLocalizedAttributesTransfer) {
                continue;
            }

            $nodeTransfer = new NavigationNodeStorageTransfer();
            $nodeTransfer->setId($navigationTreeNodeTransfer->getNavigationNode()->getIdNavigationNode());
            $nodeTransfer->setTitle($navigationNodeLocalizedAttributes->getTitle());
            $nodeTransfer->setCssClass($navigationNodeLocalizedAttributes->getCssClass());
            $nodeTransfer->setUrl($this->getNavigationNodeUrl($navigationNodeLocalizedAttributes));
            $nodeTransfer->setNodeType($navigationTreeNodeTransfer->getNavigationNode()->getNodeType());
            $nodeTransfer->setIsActive($navigationTreeNodeTransfer->getNavigationNode()->getIsActive());
            $nodeTransfer->setValidFrom($navigationTreeNodeTransfer->getNavigationNode()->getValidFrom());
            $nodeTransfer->setValidTo($navigationTreeNodeTransfer->getNavigationNode()->getValidTo());
            $nodeTransfer->setProductAssignments(
                $this->getProductAssignments($navigationTreeNodeTransfer->getNavigationNode())
            );
            $nodeTransfer->setStorageModifiedAt(time());

            if ($navigationTreeNodeTransfer->getChildren()->count() > 0) {
                $nodeTransfer->setChildren($this->mapToNavigationNodeStorageTransfer($navigationTreeNodeTransfer->getChildren()));
            }

            $nodes[] = $nodeTransfer;
        }

        return $nodes;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return mixed[]
     */
    protected function getProductAssignments(NavigationNodeTransfer $navigationNodeTransfer): array
    {
        if (!$navigationNodeTransfer->getProductAssignments()) {
            return [];
        }

        $productAssignmentString = sprintf('[%s]', $navigationNodeTransfer->getProductAssignments());
        $productAssignments = $this->utilEncodingService->decodeJson($productAssignmentString, true);
        $productListsCombinations = [];

        foreach ($productAssignments as $productAssignment) {
            unset($productAssignment['id_product_abstract']);
            $productListsCombinations[] = $productAssignment;
        }

        return array_unique($productListsCombinations, SORT_REGULAR);
    }
}
