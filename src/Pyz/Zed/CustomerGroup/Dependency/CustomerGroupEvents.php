<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Dependency;

interface CustomerGroupEvents
{
    /**
     * Specification
     * - This events will be used for spy_customer_group publishing
     *
     * @api
     */
    public const CUSTOMER_GROUP_PUBLISH = 'Entity.spy_customer_group.publish';

    /**
     * Specification
     * - This events will be used for spy_customer_group un-publishing
     *
     * @api
     */
    public const CUSTOMER_GROUP_UNPUBLISH = 'Entity.spy_customer_group.unpublish';

    public const ENTITY_SPY_CUSTOMER_GROUP_CREATE = 'Entity.spy_customer_group.create';
    public const ENTITY_SPY_CUSTOMER_GROUP_UPDATE = 'Entity.spy_customer_group.update';
    public const ENTITY_SPY_CUSTOMER_GROUP_DELETE = 'Entity.spy_customer_group.delete';
}
