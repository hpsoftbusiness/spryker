<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Dependency;

interface ProductAttributeEvents
{
    /**
     * Specification
     * - This event will be used for spy_product_management_attribute entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_UPDATE = 'Entity.spy_product_management_attribute.update';

    /**
     * Specification
     * - This event will be used for spy_product_management_attribute entity created
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_CREATE = 'Entity.spy_product_management_attribute.create';

    /**
     * Specification
     * - This event will be used for spy_product_management_attribute entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_DELETE = 'Entity.spy_product_management_attribute.delete';
}
