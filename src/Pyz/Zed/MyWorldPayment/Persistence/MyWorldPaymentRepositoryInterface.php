<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence;

use Generated\Shared\Transfer\PaymentDataResponseTransfer;

interface MyWorldPaymentRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDataResponseTransfer|null
     */
    public function findMyWorldPaymentByIdSalesOrder(int $idSalesOrder): ?PaymentDataResponseTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    public function findOrderMyWorldPaymentsByIdSalesOrder(int $idSalesOrder): array;
}
