<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use SprykerEco\Yves\Adyen\AdyenConfig as SprykerAdyenConfig;

class AdyenConfig extends SprykerAdyenConfig
{
    /**
     * @return int
     */
    public function getPaymentOptionIdEVoucher(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_EVOUCHER);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdEVoucherOnBehalfOfMarketer(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdCashback(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT);
    }
}
