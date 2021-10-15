<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp;

use Pyz\Shared\Weclapp\WeclappConfig as SharedWeclappConfig;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class WeclappConfig extends AbstractBundleConfig
{
    protected const EVENT_SHIP_THE_ORDER = 'ship the order';

    /**
     * @api
     *
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return SharedWeclappConfig::WECLAPP_QUEUE;
    }

    /**
     * @return string
     */
    public function getShipTheOrderEvent(): string
    {
        return static::EVENT_SHIP_THE_ORDER;
    }
}
