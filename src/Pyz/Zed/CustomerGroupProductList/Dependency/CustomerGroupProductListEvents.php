<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Dependency;

interface CustomerGroupProductListEvents
{
    public const ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_CREATE = 'Entity.pyz_customer_group_to_product_list.create';
    public const ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_UPDATE = 'Entity.pyz_customer_group_to_product_list.update';
    public const ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_DELETE = 'Entity.pyz_customer_group_to_product_list.delete';
}
