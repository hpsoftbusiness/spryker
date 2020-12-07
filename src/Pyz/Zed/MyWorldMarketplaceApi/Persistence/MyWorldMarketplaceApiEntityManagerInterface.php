<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
