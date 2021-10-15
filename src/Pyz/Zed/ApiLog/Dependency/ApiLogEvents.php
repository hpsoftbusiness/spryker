<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Dependency;

interface ApiLogEvents
{
    public const NEW_OUTBOUND_LOG = 'NEW_OUTBOUND_LOG';
    public const NEW_INBOUND_LOG = 'NEW_INBOUND_LOG';
}
