<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business;

use Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\OmsFacade as SprykerOmsFacade;

/**
 * @method \Pyz\Zed\Oms\Business\OmsBusinessFactory getFactory()
 */
class OmsFacade extends SprykerOmsFacade implements OmsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer
     */
    public function calculateInitiationTimeout(TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer): TimeoutProcessorTimeoutResponseTransfer
    {
        return $this->getFactory()->createInitiationTimeoutCalculator()->calculateTimeout($timeoutProcessorTimeoutRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendShippingConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        $this->getFactory()->createMailHandler()->sendShippingConfirmationMail($salesOrderEntity);
    }
}
