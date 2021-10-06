<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttributeStorage;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductAttributeStorage\ProductAttributeStorageFactory getFactory()
 */
class ProductAttributeStorageClient extends AbstractClient implements ProductAttributeStorageClientInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(): ProductAttributeKeysCollectionTransfer
    {
        return $this->getFactory()->createProductAttributeStorageReader()->getKeysToShowOnPdp();
    }
}
