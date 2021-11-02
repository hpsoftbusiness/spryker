<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapper;
use Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface;
use Pyz\Client\MyWorldPayment\Reader\MyWorldPaymentReader;
use Pyz\Client\MyWorldPayment\Reader\MyWorldPaymentReaderInterface;
use Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub;
use Pyz\Service\Customer\CustomerServiceInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

/**
 * @method \Pyz\Client\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldPaymentFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\MyWorldPayment\Zed\MyWorldPaymentStub
     */
    public function createZedStub(): MyWorldPaymentStub
    {
        return new MyWorldPaymentStub(
            $this->getZedRequestClient(),
            $this->createQuoteResponseTransferMapper()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\Mapper\QuoteResponseTransferMapperInterface
     */
    public function createQuoteResponseTransferMapper(): QuoteResponseTransferMapperInterface
    {
        return new QuoteResponseTransferMapper();
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\Reader\MyWorldPaymentReaderInterface
     */
    public function createMyWorldPaymentReader(): MyWorldPaymentReaderInterface
    {
        return new MyWorldPaymentReader(
            $this->getCustomerClient(),
            $this->getCustomerService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    protected function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Pyz\Service\Customer\CustomerServiceInterface
     */
    protected function getCustomerService(): CustomerServiceInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::SERVICE_CUSTOMER);
    }
}
