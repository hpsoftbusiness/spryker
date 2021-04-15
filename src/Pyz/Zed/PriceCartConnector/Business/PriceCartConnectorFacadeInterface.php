<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface as SprykerPriceCartConnectorFacadeInterface;

interface PriceCartConnectorFacadeInterface extends SprykerPriceCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expands cart change items with benefit prices (i.e. Shopping points price).
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitPrices(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
