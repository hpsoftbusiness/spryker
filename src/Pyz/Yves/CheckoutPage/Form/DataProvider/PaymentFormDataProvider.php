<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\Currency\CurrencyClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Pyz\Yves\CheckoutPage\Form\Steps\PaymentForm;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

class PaymentFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    private $subFormDataProviders;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    private $myWorldMarketingApiClient;

    /**
     * @var \Pyz\Client\Currency\CurrencyClientInterface
     */
    private $currencyClient;

    /**
     * @var \Pyz\Yves\CheckoutPage\CheckoutPageConfig
     */
    private $config;

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface $subFormDataProviders
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $myWorldMarketingApiClient
     * @param \Pyz\Client\Currency\CurrencyClientInterface $currencyClient
     * @param \Pyz\Yves\CheckoutPage\CheckoutPageConfig $config
     */
    public function __construct(
        StepEngineFormDataProviderInterface $subFormDataProviders,
        MyWorldMarketplaceApiClientInterface $myWorldMarketingApiClient,
        CurrencyClientInterface $currencyClient,
        CheckoutPageConfig $config
    ) {
        $this->subFormDataProviders = $subFormDataProviders;
        $this->myWorldMarketingApiClient = $myWorldMarketingApiClient;
        $this->currencyClient = $currencyClient;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        return $this->subFormDataProviders->getData($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $options = $this->subFormDataProviders->getOptions($quoteTransfer);
        $currencyIsoCode = $this->currencyClient->getCurrent()->getCode();

        if ($this->config->isSsoLoginEnabled()) {
            $customerBalances = $this->myWorldMarketingApiClient->getCustomerBalanceByCurrency(
                $quoteTransfer->getCustomer(),
                $currencyIsoCode
            );
        } else {
            $customerBalances = [];
        }

        return array_merge(
            $options,
            [
                PaymentForm::OPTION_KEY_CUSTOMER_BALANCES => $customerBalances,
                PaymentForm::OPTION_KEY_CURRENCY_CODE => $currencyIsoCode,
            ]
        );
    }
}
