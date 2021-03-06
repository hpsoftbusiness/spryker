<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeStorageTransfer;
use Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriter as SprykerNavigationStorageWriter;

class NavigationStorageWriter extends SprykerNavigationStorageWriter
{
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
            $nodeTransfer->setIdCategory($navigationTreeNodeTransfer->getNavigationNode()->getIdCategory());
            $nodeTransfer->setStorageModifiedAt(time());

            if ($navigationTreeNodeTransfer->getChildren()->count() > 0) {
                $nodeTransfer->setChildren($this->mapToNavigationNodeStorageTransfer($navigationTreeNodeTransfer->getChildren()));
            }

            $nodes[] = $nodeTransfer;
        }

        return $nodes;
    }
}
