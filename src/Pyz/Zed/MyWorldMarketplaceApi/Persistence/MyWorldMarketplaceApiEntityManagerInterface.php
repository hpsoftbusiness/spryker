<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

interface MyWorldMarketplaceApiEntityManagerInterface
{
    /**
     * @param int[] $orderItemIds
     * @param bool $isTurnoverCreated
     *
     * @return void
     */
    public function setIsTurnoverCreated(array $orderItemIds, bool $isTurnoverCreated = true): void;

    /**
     * @param int[] $orderItemIds
     * @param bool $isTurnoverCancelled
     *
     * @return void
     */
    public function setIsTurnoverCancelled(array $orderItemIds, bool $isTurnoverCancelled = true): void;
}
