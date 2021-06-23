<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Discount;

use Spryker\Shared\Discount\DiscountConstants as SprykerDiscountConstants;

interface DiscountConstants extends SprykerDiscountConstants
{
    /**
     * Specification:
     * - Represents internal discount type not manageable through ZED backoffice.
     *
     * @api
     */
    public const TYPE_INTERNAL_DISCOUNT = 'internal_discount';
}
