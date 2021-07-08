<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageClient as SprykerProductLabelStorageClient;

class ProductLabelStorageClient extends SprykerProductLabelStorageClient implements ProductLabelStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer
     */
    public function getFirstLabelByIdProductAbstract(int $idProductAbstract, string $localeName, string $storeName): ProductLabelDictionaryItemTransfer
    {
        $labels = $this->findLabelsByIdProductAbstract($idProductAbstract, $localeName, $storeName);

        return reset($labels);
    }
}
