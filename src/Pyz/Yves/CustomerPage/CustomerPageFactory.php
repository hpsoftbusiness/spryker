<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage;

use Pyz\Client\CustomerGroup\CustomerGroupClientInterface;
use Pyz\Client\Locale\LocaleClientInterface;
use Pyz\Client\Sso\SsoClientInterface;
use Pyz\Yves\CustomerPage\CustomerAddress\AddressChoicesResolver;
use Pyz\Yves\CustomerPage\Form\FormFactory;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider;
use Pyz\Yves\CustomerPage\UserChecker\CustomerConfirmationUserChecker;
use Spryker\Client\Country\CountryClientInterface;
use SprykerShop\Yves\CustomerPage\CustomerAddress\AddressChoicesResolverInterface;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory as SprykerCustomerPageFactory;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * @method \Pyz\Client\Customer\CustomerClientInterface getCustomerClient() : CustomerPageToCustomerClientInterface
 */
class CustomerPageFactory extends SprykerCustomerPageFactory
{
    /**
     * @return \Pyz\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider|\SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider|\Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createCustomerUserProvider()
    {
        return new CustomerUserProvider($this->getSsoClient());
    }

    /**
     * @return \Pyz\Client\Sso\SsoClientInterface
     */
    public function getSsoClient(): SsoClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_SSO);
    }

    /**
     * @return \Pyz\Yves\CustomerPage\Form\FormFactory
     */
    public function createCustomerFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createCustomerAuthenticationSuccessHandler()
    {
        return new CustomerAuthenticationSuccessHandler($this->getFlashMessenger());
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    public function createCustomerConfirmationUserChecker(): UserCheckerInterface
    {
        return new CustomerConfirmationUserChecker($this->getSsoClient());
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\CustomerAddress\AddressChoicesResolverInterface
     */
    public function createAddressChoicesResolver(): AddressChoicesResolverInterface
    {
        return new AddressChoicesResolver($this->getCountryClient());
    }

    /**
     * @return \Spryker\Client\Country\CountryClientInterface
     */
    public function getCountryClient(): CountryClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_COUNTRY);
    }

    /**
     * @return \Pyz\Client\CustomerGroup\CustomerGroupClientInterface
     */
    public function getCustomerGroupClient(): CustomerGroupClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_CUSTOMER_GROUP);
    }

    /**
     * @return \Pyz\Client\Locale\LocaleClientInterface
     */
    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_LOCALE);
    }
}
