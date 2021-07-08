<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form;

use Pyz\Client\Currency\CurrencyClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\DataProvider\PaymentFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\DataProvider\SummaryFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\BenefitDealCollectionForm;
use Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\Transformer\BenefitVoucherAmountTransformer;
use Pyz\Yves\CheckoutPage\Form\Steps\PaymentForm;
use Pyz\Yves\CheckoutPage\Form\Steps\SummaryForm;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use SprykerShop\Yves\CheckoutPage\Form\FormFactory as SpyFormFactory;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class FormFactory extends SpyFormFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function getPaymentFormCollection(): FormCollectionHandlerInterface
    {
        $formDataProvider = $this->getConfig()->isCashbackFeatureEnabled()
            ? $this->createPaymentFormDataProvider()
            : $this->createSubFormDataProvider($this->getPaymentMethodSubForms());

        return $this->createSubFormCollection(PaymentForm::class, $formDataProvider);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function getBenefitFormCollection(): FormCollectionHandlerInterface
    {
        return $this->createFormCollection(
            [BenefitDealCollectionForm::class],
            $this->createBenefitDealFormDataProvider()
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider
     */
    public function createBenefitDealFormDataProvider(): BenefitFormDataProvider
    {
        return new BenefitFormDataProvider();
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSummaryFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SummaryFormDataProvider(
            $this->getConfig(),
            $this->getLocaleClient(),
            $this->getGlossaryStorageClient()
        );
    }

    /**
     * @return string
     */
    public function getSummaryForm()
    {
        return SummaryForm::class;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaymentFormDataProvider(): StepEngineFormDataProviderInterface
    {
        $createPaymentSubForms = $this->getPaymentMethodSubForms();
        $subFormDataProvider = $this->createSubFormDataProvider($createPaymentSubForms);

        return new PaymentFormDataProvider(
            $subFormDataProvider,
            $this->getMyWorldMarketingApiClient(),
            $this->getCurrencyClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createBenefitVoucherAmountTransformer(): DataTransformerInterface
    {
        return new BenefitVoucherAmountTransformer(
            $this->createDecimalToIntegerConverter(),
            $this->createIntegerToDecimalConverter()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    public function getMyWorldMarketingApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_MY_WORLD_MARKETING_API);
    }

    /**
     * @return \Pyz\Client\Currency\CurrencyClientInterface
     */
    public function getCurrencyClient(): CurrencyClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function createDecimalToIntegerConverter(): DecimalToIntegerConverterInterface
    {
        return new DecimalToIntegerConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    public function createIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return new IntegerToDecimalConverter();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createAddressFormCollection()
    {
        return $this->createFormCollection($this->getAddressFormTypes(), $this->getCheckoutAddressFormDataProviderPlugin());
    }
}
