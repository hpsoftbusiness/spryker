<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface as SprykerProductCartConnectorFacadeInterface;

interface ProductCartConnectorFacadeInterface extends SprykerProductCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expand cart change items with benefit deals data.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitDeals(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Expand cart change items with benefit deals charge amount data.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitDealsChargeAmount(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
