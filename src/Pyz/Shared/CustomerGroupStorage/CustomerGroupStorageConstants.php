<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\CustomerGroupStorage;

class CustomerGroupStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing availability messages
     *
     * @api
     */
    public const CUSTOMER_GROUP_SYNC_STORAGE_QUEUE = 'sync.storage.customer_group';

    /**
     * Specification:
     * - Queue name as used for processing availability messages
     *
     * @api
     */
    public const CUSTOMER_GROUP_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.customer_group.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CUSTOMER_GROUP_RESOURCE_NAME = 'customer_group';
}
