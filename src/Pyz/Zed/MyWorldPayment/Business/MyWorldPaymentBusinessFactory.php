<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business;

use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\CashbackPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\EVoucherMarketerPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\EVoucherPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumberCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\ShoppingPointsPaymentCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\TurnoverCalculator;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\BenefitVoucherDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\CashbackDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\EVoucherDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\EVoucherMarketerDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\ShoppingPointsDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGenerator;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGeneratorInterface;
use Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLog;
use Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface;
use Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\PaymentPriceManagerInterface;
use Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\QuotePaymentPriceManager;
use Pyz\Zed\MyWorldPayment\Business\Refund\RefundProcessor;
use Pyz\Zed\MyWorldPayment\Business\Refund\RefundProcessorInterface;
use Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\MyWorldPaymentApiRequestDispatcher;
use Pyz\Zed\MyWorldPayment\Business\RequestDispatcher\RequestDispatcherInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;

/**
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface getRepository()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentEntityManagerInterface getEntityManager()
 */
class MyWorldPaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface
     */
    public function getMyWorldPaymentApiFacade(): MyWorldPaymentApiFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::FACADE_MY_WORLD_PAYMENT_API);
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
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface
     */
    public function createEVoucherPaymentCalculator(): MyWorldPaymentQuoteCalculatorInterface
    {
        return new EVoucherPaymentCalculator(
            $this->getCustomerService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface
     */
    public function createCashbackPaymentCalculator(): MyWorldPaymentQuoteCalculatorInterface
    {
        return new CashbackPaymentCalculator(
            $this->getCustomerService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface
     */
    public function createEVoucherMarketerPaymentCalculator(): MyWorldPaymentQuoteCalculatorInterface
    {
        return new EVoucherMarketerPaymentCalculator(
            $this->getCustomerService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function getDecimalToIntegerConverter(): DecimalToIntegerConverterInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::DECIMAL_TO_INTEGER_CONVERTER);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface|\Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface
     */
    public function createBenefitVoucherPaymentCalculator()
    {
        return new BenefitVoucherPaymentCalculator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface|\Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface
     */
    public function createShoppingPointsPaymentCalculator()
    {
        return new ShoppingPointsPaymentCalculator($this->getCustomerService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface|\Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface
     */
    public function createTurnoverCalculator()
    {
        return new TurnoverCalculator();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface|\Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface
     */
    public function createSegmentNumberCalculator()
    {
        return new SegmentNumberCalculator();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\MyWorldPaymentRequestApiTransferGeneratorInterface
     */
    public function createApiRequestGenerator(): MyWorldPaymentRequestApiTransferGeneratorInterface
    {
        return new MyWorldPaymentRequestApiTransferGenerator(
            $this->getConfig(),
            $this->getSequenceFacade(),
            $this->createPaymentFlowsTransferGenerator(),
            $this->createPaymentRefundRequestTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::FACADE_SEQUENCE);
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
     * @return \Pyz\Zed\MyWorldPayment\Business\Refund\RefundProcessorInterface
     */
    public function createRefundProcessor(): RefundProcessorInterface
    {
        return new RefundProcessor(
            $this->getRepository(),
            $this->createMyWorldPaymentApiRequestDispatcher()
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PaymentRefundRequestTransferGeneratorInterface
     */
    public function createPaymentRefundRequestTransferGenerator(): PaymentRefundRequestTransferGeneratorInterface
    {
        return new PaymentRefundRequestTransferGenerator(
            $this->createPartialRefundTransferGenerator()
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGeneratorInterface
     */
    public function createPartialRefundTransferGenerator(): PartialRefundTransferGeneratorInterface
    {
        return new PartialRefundTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\PaymentFlow\PaymentFlowsTransferGeneratorInterface
     */
    public function createPaymentFlowsTransferGenerator(): PaymentFlowsTransferGeneratorInterface
    {
        return new PaymentFlowsTransferGenerator(
            $this->getConfig(),
            $this->getDirectPaymentTransferGeneratorsStack()
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\DirectPaymentTransferGeneratorInterface[]
     */
    public function getDirectPaymentTransferGeneratorsStack(): array
    {
        return [
            $this->createBenefitVoucherDirectPaymentTransferGenerator(),
            $this->createCashbackDirectPaymentTransferGenerator(),
            $this->createEVoucherDirectPaymentTransferGenerator(),
            $this->createEVoucherMarketerDirectPaymentTransferGenerator(),
            $this->createShoppingPointsDirectPaymentTransferGenerator(),
        ];
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\BenefitVoucherDirectPaymentTransferGenerator
     */
    public function createBenefitVoucherDirectPaymentTransferGenerator(): BenefitVoucherDirectPaymentTransferGenerator
    {
        return new BenefitVoucherDirectPaymentTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\CashbackDirectPaymentTransferGenerator
     */
    public function createCashbackDirectPaymentTransferGenerator(): CashbackDirectPaymentTransferGenerator
    {
        return new CashbackDirectPaymentTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\EVoucherDirectPaymentTransferGenerator
     */
    public function createEVoucherDirectPaymentTransferGenerator(): EVoucherDirectPaymentTransferGenerator
    {
        return new EVoucherDirectPaymentTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\EVoucherMarketerDirectPaymentTransferGenerator
     */
    public function createEVoucherMarketerDirectPaymentTransferGenerator(): EVoucherMarketerDirectPaymentTransferGenerator
    {
        return new EVoucherMarketerDirectPaymentTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\ShoppingPointsDirectPaymentTransferGenerator
     */
    public function createShoppingPointsDirectPaymentTransferGenerator(): ShoppingPointsDirectPaymentTransferGenerator
    {
        return new ShoppingPointsDirectPaymentTransferGenerator($this->getConfig());
    }

    /**
     * @return \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    public function getCustomerGroupQuery(): CustomerGroupQueryContainerInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::QUERY_CONTAINER_CUSTOMER_GROUP);
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\PaymentApiLog\PaymentApiLogInterface
     */
    public function createPaymentApiLog(): PaymentApiLogInterface
    {
        return new PaymentApiLog();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager\PaymentPriceManagerInterface
     */
    public function createPaymentPriceManager(): PaymentPriceManagerInterface
    {
        return new QuotePaymentPriceManager();
    }

    /**
     * @return \Pyz\Service\Customer\CustomerServiceInterface
     */
    public function getCustomerService(): CustomerServiceInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::SERVICE_CUSTOMER);
    }
}
