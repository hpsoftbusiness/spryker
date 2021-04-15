<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business;

use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\EVoucherPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentCalculatorInterface;
use Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLog;
use Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface;
use Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\MyWorldPaymentApiRequestDispatcher;
use Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;

/**
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldPaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface
     */
    public function getMyWorldPaymentApiFacade(): MyWorldPaymentApiFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::MY_WORLD_PAYMENT_API_FACADE);
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    public function getMyWorldMarketplaceApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::MY_WORLD_MARKETPLACE_API_CLIENT);
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClientInterface
     */
    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::LOCALE_CLIENT);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentCalculatorInterface
     */
    public function createEVoucherPaymentCalculator(): MyWorldPaymentCalculatorInterface
    {
        return new EVoucherPaymentCalculator($this->getMyWorldMarketplaceApiClient(), $this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentCalculatorInterface
     */
    public function createBenefitVoucherPaymentCalculator(): MyWorldPaymentCalculatorInterface
    {
        return new BenefitVoucherPaymentCalculator(
            $this->getMyWorldMarketplaceApiClient(),
            $this->getProductStorageClient(),
            $this->getLocaleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface
     */
    public function createApiRequestGenerator(): MyWorldPaymentRequestApiTransferGeneratorInterface
    {
        return new MyWorldPaymentRequestApiTransferGenerator($this->getConfig(), $this->getSequenceFacade());
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::SEQUENCE_FACADE);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface
     */
    public function createMyWorldPaymentApiRequestDispatcher(): RequestDispatcherInterface
    {
        return new MyWorldPaymentApiRequestDispatcher(
            $this->createApiRequestGenerator(),
            $this->getMyWorldPaymentApiFacade(),
            $this->createPaymentApiLog()
        );
    }

    /**
     * @return \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    public function getCustomerGroupQuery(): CustomerGroupQueryContainerInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CUSTOMER_GROUP_QUERY);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface
     */
    public function createPaymentApiLog(): PaymentApiLogInterface
    {
        return new PaymentApiLog();
    }
}
