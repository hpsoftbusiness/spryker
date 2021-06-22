<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business;

use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface as SprykerProductCartConnectorFacadeInterface;

interface ProductCartConnectorFacadeInterface extends SprykerProductCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expand items with benefit deals data.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function expandItemsWithBenefitDeals(iterable $itemTransfers): void;

    /**
     * Specification:
     * - Expand items with benefit deals charge amount data.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function expandItemsWithBenefitDealsChargeAmount(iterable $itemTransfers): void;
}
