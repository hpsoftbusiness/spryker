<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\Customer\CustomerServiceInterface as SprykerCustomerServiceInterface;

interface CustomerServiceInterface extends SprykerCustomerServiceInterface
{
    /**
     * Specification:
     * - Searches for shopping point balance in CustomerTransfer balances array.
     * - Returns amount as float value.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return float
     */
    public function getCustomerShoppingPointsBalanceAmount(CustomerTransfer $customerTransfer): float;

    /**
     * Specification:
     * - Searches for eVoucher balance in CustomerTransfer balances array.
     * - Returns integer value (in cents).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerEVoucherBalanceAmount(CustomerTransfer $customerTransfer): int;

    /**
     * Specification:
     * - Searches for Cashback balance in CustomerTransfer balances array.
     * - Returns integer value (in cents).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerCashbackBalanceAmount(CustomerTransfer $customerTransfer): int;

    /**
     * Specification:
     * - Searches for eVoucher on behalf of marketer balance in CustomerTransfer balances array.
     * - Returns integer value (in cents).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerMarketerEVoucherBalanceAmount(CustomerTransfer $customerTransfer): int;

    /**
     * Specification:
     * - Searches for Benefit Voucher balance in CustomerTransfer balances array.
     * - Returns integer value (in cents).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerBenefitVoucherBalanceAmount(CustomerTransfer $customerTransfer): int;

    /**
     * Specification:
     * - Searches for internal payment balance by provided payment option ID in CustomerTransfer balances array.
     * - Returns integer value (in cents).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $idPaymentOption
     *
     * @return int
     */
    public function getCustomerBalanceAmountByPaymentOptionId(CustomerTransfer $customerTransfer, int $idPaymentOption): int;
}
