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
    public const MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL = 'OMS:MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL';

    /**
     * Specification:
     * - Defines a recipient's name for the order-in-processing mail.
     *
     * @api
     */
    public const MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME = 'OMS:MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME';

    /**
     * Specification:
     * - Defines a recipient's name for the shipping-confirmation mail.
     *
     * @api
     */
    public const MAIL_SHIPPING_CONFIRMATION_BCC_RECIPIENT_NAME = 'OMS:MAIL_SHIPPING_CONFIRMATION_BCC_RECIPIENT_NAME';

    /**
     * Specification:
     * - Defines a recipient's email for the shipping-confirmation mail.
     *
     * @api
     */
    public const MAIL_SHIPPING_CONFIRMATION_BCC_RECIPIENT_EMAIL = 'OMS:MAIL_SHIPPING_CONFIRMATION_BCC_RECIPIENT_EMAIL';
}
