<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Adyen\Business\AdyenFacadeInterface as SprykerEcoAdyenFacadeInterface;

interface AdyenFacadeInterface extends SprykerEcoAdyenFacadeInterface
{
    /**
     * Specification:
     * - Expands order with payment adyen transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithPaymentAdyen(OrderTransfer $orderTransfer): OrderTransfer;
}
