<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage;

use Pyz\Client\Sso\SsoClientInterface;
use Pyz\Yves\CustomerPage\Form\FormFactory;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory as SprykerCustomerPageFactory;

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
}
