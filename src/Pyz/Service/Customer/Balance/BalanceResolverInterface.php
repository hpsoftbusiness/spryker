<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer\Balance;

use Generated\Shared\Transfer\CustomerTransfer;

interface BalanceResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $paymentOptionId
     *
     * @return int
     */
    public function resolveBalanceAmount(CustomerTransfer $customerTransfer, int $paymentOptionId): int;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $paymentOptionId
     *
     * @return float
     */
    public function resolveBalanceAmountWithoutConversion(CustomerTransfer $customerTransfer, int $paymentOptionId): float;
}
