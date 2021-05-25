<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Business;

use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface as SprykerPriceProductFacadeInterface;

interface PriceProductFacadeInterface extends SprykerPriceProductFacadeInterface
{
    /**
     * Specification:
     * - Returns Shopping Points price type name.
     *
     * @return string
     */
    public function getBenefitPriceTypeName(): string;
}
