<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence;

use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentPersistenceFactory getFactory()
 */
class MyWorldPaymentEntityManager extends AbstractEntityManager implements MyWorldPaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $dataResponseTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveMyWorldPayment(PaymentDataResponseTransfer $dataResponseTransfer, int $idSalesOrder): void {
        $myWorldPaymentEntity = new PyzPaymentMyWorld();
        $myWorldPaymentEntity = $this->getFactory()->createMyWorldPaymentMapper()
            ->mapPaymentDataResponseTransferToEntity($dataResponseTransfer, $myWorldPaymentEntity);
        $myWorldPaymentEntity->setFkSalesOrder($idSalesOrder);
        $myWorldPaymentEntity->save();
    }
}
