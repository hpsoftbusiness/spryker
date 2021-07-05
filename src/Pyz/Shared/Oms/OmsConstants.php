<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Oms;

use Spryker\Shared\Oms\OmsConstants as SprykerOmsConstants;

interface OmsConstants extends SprykerOmsConstants
{
    /**
     * Specification:
     * - Defines a recipient's email for the order-in-processing mail.
     *
     * @api
     */
    public const MAIL_ORDER_IN_PROCESSING_RECIPIENTS = 'OMS:MAIL_ORDER_IN_PROCESSING_RECIPIENTS';
}
