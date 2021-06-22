<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence;

use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper\MyWorldPaymentMapper;
use Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper\PaymentMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface getRepository()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentEntityManagerInterface getEntityManager()
 */
class MyWorldPaymentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldQuery
     */
    public function createPyzPaymentMyWorldQuery(): PyzPaymentMyWorldQuery
    {
        return PyzPaymentMyWorldQuery::create();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper\MyWorldPaymentMapper
     */
    public function createMyWorldPaymentMapper(): MyWorldPaymentMapper
    {
        return new MyWorldPaymentMapper();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function createSpySalesPaymentQuery(): SpySalesPaymentQuery
    {
        return SpySalesPaymentQuery::create();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper\PaymentMapper
     */
    public function createPaymentMapper(): PaymentMapper
    {
        return new PaymentMapper();
    }
}
