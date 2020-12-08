<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

interface MyWorldMarketplaceApiEntityManagerInterface
{
    /**
     * @param string $orderReference
     * @param bool $isTurnoverCreated
     *
     * @return void
     */
    public function setIsTurnoverCreated(string $orderReference, bool $isTurnoverCreated = true): void;

    /**
     * @param string $orderReference
     * @param bool $isTurnoverCancelled
     *
     * @return void
     */
    public function setIsTurnoverCancelled(string $orderReference, bool $isTurnoverCancelled = true): void;
}
