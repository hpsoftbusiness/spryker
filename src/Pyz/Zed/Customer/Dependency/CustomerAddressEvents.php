<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Dependency;

interface CustomerAddressEvents
{
    public const ENTITY_SPY_CUSTOMER_ADDRESS_CREATE = 'Entity.spy_customer_address.create';
    public const ENTITY_SPY_CUSTOMER_ADDRESS_UPDATE = 'Entity.spy_customer_address.update';
}
