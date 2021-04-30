<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Business;

use Pyz\Zed\Payment\Business\Order\SalesPaymentHydrator;
use Pyz\Zed\Payment\Business\Order\SalesPaymentSaver;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface;
use Spryker\Zed\Payment\Business\PaymentBusinessFactory as SprykerPaymentBusinessFactory;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 */
class PaymentBusinessFactory extends SprykerPaymentBusinessFactory
{
    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentHydratorInterface
     */
    public function createPaymentHydrator()
    {
        return new SalesPaymentHydrator(
            $this->getPaymentHydrationPlugins(),
            $this->getQueryContainer(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Business\Order\SalesPaymentSaverInterface
     */
    protected function createPaymentSaver(): SalesPaymentSaverInterface
    {
        return new SalesPaymentSaver($this->getQueryContainer());
    }
}
