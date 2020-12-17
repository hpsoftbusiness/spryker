<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms;

use Pyz\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\OmsConfig as SprykerOmsConfig;

class OmsConfig extends SprykerOmsConfig
{
    /**
     * @return string
     */
    public function getFallbackDisplayNamePrefix(): string
    {
        return 'oms.state.';
    }

    /**
     * @return string
     */
    public function getMailOrderInProcessingRecipientEmail(): string
    {
        return $this->get(OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL);
    }

    /**
     * @return string
     */
    public function getMailOrderInProcessingRecipientName(): string
    {
        return $this->get(OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME);
    }
}
