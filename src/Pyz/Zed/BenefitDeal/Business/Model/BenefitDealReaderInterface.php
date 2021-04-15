<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface BenefitDealReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithBenefitDeal(OrderTransfer $orderTransfer): OrderTransfer;
}
