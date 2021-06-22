<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface RefundDetailsCollectorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function collect(OrderTransfer $orderTransfer): array;
}
