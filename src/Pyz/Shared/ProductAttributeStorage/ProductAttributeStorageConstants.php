<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\ProductAttributeStorage;

interface ProductAttributeStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing product attributes
     *
     * @api
     */
    public const PRODUCT_ATTRIBUTE_SYNC_STORAGE_QUEUE = 'sync.storage.product_attribute';

    /**
     * Specification:
     * - Queue name as used for processing product attributes error messages
     *
     * @api
     */
    public const PRODUCT_ATTRIBUTE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_attribute.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_MANAGEMENT_ATTRIBUTE_VISIBILITY_RESOURCE_NAME = 'product_management_attribute_visibility';
}
