<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

interface MyWorldMarketplaceApiEntityManagerInterface
{
    /**
     * @param int $orderItemId
     *
     * @return void
     */
    public function setTurnoverCreated(int $orderItemId): void;

    /**
     * @param int $orderItemId
     *
     * @return void
     */
    public function setTurnoverCancelled(int $orderItemId): void;

    /**
     * @param int $orderItemId
     * @param string $turnoverReference
     *
     * @return void
     */
    public function updateTurnoverReference(int $orderItemId, string $turnoverReference): void;
}
