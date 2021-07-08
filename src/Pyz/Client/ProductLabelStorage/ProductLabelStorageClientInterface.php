<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface as SprykerProductLabelStorageClientInterface;

interface ProductLabelStorageClientInterface extends SprykerProductLabelStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves the first label for given abstract product ID, locale and store name.
     * - Only labels assigned with passed $storeName can be returned.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer
     */
    public function getFirstLabelByIdProductAbstract(int $idProductAbstract, string $localeName, string $storeName): ProductLabelDictionaryItemTransfer;
}
