<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class BenefitDealConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShoppingPointsPaymentName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_SHOPPING_POINTS);
    }
}
