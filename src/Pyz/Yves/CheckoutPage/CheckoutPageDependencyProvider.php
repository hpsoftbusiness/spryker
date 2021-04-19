<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Yves\Country\Plugin\CheckoutPage\CountryAddressExpanderPlugin;
use Pyz\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;
use Pyz\Yves\DummyPrepayment\Plugin\StepEngine\DummyPaymentStepHandlerPlugin;
use Pyz\Yves\DummyPrepayment\Plugin\StepEngine\SubForm\DummyPrepaymentSubFormPlugin;
use Pyz\Yves\MyWorldPayment\Plugin\StepEngine\MyWorldPaymentBonusSubFormPlugin;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\Payment\Plugin\PaymentFormFilterPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Shared\Adyen\AdyenConfig;
use SprykerEco\Yves\Adyen\Plugin\AdyenPaymentHandlerPlugin;
use SprykerEco\Yves\Adyen\Plugin\StepEngine\AdyenCreditCardSubFormPlugin;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider as SprykerShopCheckoutPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Form\CustomerCheckoutForm;
use SprykerShop\Yves\CustomerPage\Form\GuestForm;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CustomerAddressExpanderPlugin;
use SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CheckoutPage\SalesOrderThresholdWidgetPlugin;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class CheckoutPageDependencyProvider extends SprykerShopCheckoutPageDependencyProvider
{
    public const SERVICE_TRANSLATOR = 'translator';
    public const FORM_EXPANDER = 'FORM_EXPANDER';
    public const MY_WORLD_PAYMENT_CLIENT = 'MY_WORLD_PAYMENT_CLIENT';
    public const MY_WORLD_MARKETPLACE_CLIENT = 'MY_WORLD_MARKETPLACE_CLIENT';
    public const CONVERTER_INTEGER_TO_DECIMAL = 'CONVERTER_INTEGER_TO_DECIMAL';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->extendPaymentMethodHandler($container);
        $container = $this->extendSubFormPluginCollection($container);
        $container = $this->addTranslatorService($container);
        $container = $this->addFormExpander($container);
        $container = $this->addMyWorldPaymentClient($container);
        $container = $this->addMyWorldMarketplaceApiClient($container);
        $container = $this->addMoneyConverter($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getSummaryPageWidgetPlugins(): array
    {
        return [
            SalesOrderThresholdWidgetPlugin::class, #SalesOrderThresholdFeature
        ];
    }

    /**
     * @return mixed[]
     */
    protected function getCustomerStepSubForms(): array
    {
        return [
            LoginForm::class,
            $this->getCustomerCheckoutForm(GuestForm::class, GuestForm::BLOCK_PREFIX),
        ];
    }

    /**
     * @param string $subForm
     * @param string $blockPrefix
     *
     * @return \SprykerShop\Yves\CustomerPage\Form\CustomerCheckoutForm|\Symfony\Component\Form\FormInterface
     */
    protected function getCustomerCheckoutForm($subForm, $blockPrefix)
    {
        return $this->getFormFactory()->createNamed(
            $blockPrefix,
            CustomerCheckoutForm::class,
            null,
            [CustomerCheckoutForm::SUB_FORM_CUSTOMER => $subForm]
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    private function getFormFactory()
    {
        return (new Pimple())->getApplication()['form.factory'];
    }

    /**
     * @return string[]
     */
    protected function getAddressStepSubForms(): array
    {
        return [
            CheckoutAddressCollectionForm::class,
        ];
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected function getSubFormFilterPlugins(): array
    {
        return [
            new PaymentFormFilterPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendPaymentMethodHandler(Container $container): Container
    {
        $container->extend(static::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandlerCollection) {
            $paymentMethodHandlerCollection->add(new NopaymentHandlerPlugin(), NopaymentConfig::PAYMENT_PROVIDER_NAME);
            $paymentMethodHandlerCollection->add(new AdyenPaymentHandlerPlugin(), AdyenConfig::ADYEN_CREDIT_CARD);
            $paymentMethodHandlerCollection->add(new DummyPaymentStepHandlerPlugin(), DummyPrepaymentConfig::DUMMY_PREPAYMENT);

            return $paymentMethodHandlerCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendSubFormPluginCollection(Container $container): Container
    {
        $container->extend(static::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $subFormPluginCollection) {
            $subFormPluginCollection->add(new AdyenCreditCardSubFormPlugin());
            $subFormPluginCollection->add(new DummyPrepaymentSubFormPlugin());

            return $subFormPluginCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFormExpander(Container $container): Container
    {
        $container->set(static::FORM_EXPANDER, function () {
            if (!$this->getConfig()->isCashbackFeatureEnabled()) {
                return [];
            }

            return [
                new MyWorldPaymentBonusSubFormPlugin(),
            ];
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[]
     */
    protected function getAddressStepExecutorAddressExpanderPlugins(): array
    {
        return [
            new CustomerAddressExpanderPlugin(),
            new CountryAddressExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addTranslatorService(Container $container): Container
    {
        $container->set(static::SERVICE_TRANSLATOR, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_TRANSLATOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addMyWorldMarketplaceApiClient(Container $container): Container
    {
        $container->set(static::MY_WORLD_MARKETPLACE_CLIENT, function (Container $container) {
            return $container->getLocator()->myWorldMarketplaceApi()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addMyWorldPaymentClient(Container $container): Container
    {
        $container->set(static::MY_WORLD_PAYMENT_CLIENT, function (Container $container) {
            return $container->getLocator()->myWorldPayment()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addMoneyConverter(Container $container): Container
    {
        $container->set(static::CONVERTER_INTEGER_TO_DECIMAL, function () {
            return new IntegerToDecimalConverter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return $container->getLocator()->productStorage()->client();
        });

        return $container;
    }
}
