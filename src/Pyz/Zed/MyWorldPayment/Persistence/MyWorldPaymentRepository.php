<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence;

use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentPersistenceFactory getFactory()
 */
class MyWorldPaymentRepository extends AbstractRepository implements MyWorldPaymentRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDataResponseTransfer|null
     */
    public function findMyWorldPaymentByIdSalesOrder(int $idSalesOrder): ?PaymentDataResponseTransfer
    {
        $entity = $this->getFactory()->createPyzPaymentMyWorldQuery()
            ->findOneByFkSalesOrder($idSalesOrder);

        if (!$entity) {
            return null;
        }

        return $this->getFactory()->createMyWorldPaymentMapper()->mapEntityToPaymentDataResponseTransfer(
            $entity,
            new PaymentDataResponseTransfer()
        );
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    public function findOrderMyWorldPaymentsByIdSalesOrder(int $idSalesOrder): array
    {
        $paymentEntities = $this->getFactory()->createSpySalesPaymentQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->useSalesPaymentMethodTypeQuery()
            ->filterByPaymentProvider(MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD)
            ->endUse()
            ->find();

        return $this->getFactory()->createPaymentMapper()->mapSalesPaymentEntityCollectionToTransfers($paymentEntities);
    }
}
