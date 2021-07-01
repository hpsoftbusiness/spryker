<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface OrderItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $originalItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $transformedItemTransfer
     *
     * @return void
     */
    public function transform(ItemTransfer $originalItemTransfer, ItemTransfer $transformedItemTransfer): void;
}
