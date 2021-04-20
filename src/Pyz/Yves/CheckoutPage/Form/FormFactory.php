<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form;

use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\DataProvider\SummaryFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\BenefitDealCollectionForm;
use Pyz\Yves\CheckoutPage\Form\Steps\PaymentForm;
use Pyz\Yves\CheckoutPage\Form\Steps\SummaryForm;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use SprykerShop\Yves\CheckoutPage\Form\FormFactory as SpyFormFactory;

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
        $createPaymentSubForms = $this->getPaymentMethodSubForms();
        $subFormDataProvider = $this->createSubFormDataProvider($createPaymentSubForms);

        return $this->createSubFormCollection(PaymentForm::class, $subFormDataProvider);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function getBenefitFormCollection(): FormCollectionHandlerInterface
    {
        return $this->createFormCollection([BenefitDealCollectionForm::class], $this->createBenefitDealFormDataProvider());
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
}
