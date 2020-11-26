<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Pyz\Client\Sso\SsoClientInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider as SprykerCustomerUserProvider;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerUserProvider extends SprykerCustomerUserProvider
{
    /**
     * @var \Pyz\Client\Sso\SsoClientInterface
     */
    protected $ssoClient;

    /**
     * @param \Pyz\Client\Sso\SsoClientInterface $ssoClient
     */
    public function __construct(SsoClientInterface $ssoClient)
    {
        $this->ssoClient = $ssoClient;
    }

    /**
     * @param string $username
     *
     * @return \SprykerShop\Yves\CustomerPage\Security\Customer|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
    {
        if ($username instanceof SsoAccessTokenTransfer) {
            return $this->loadUserBySsoAccessToken($username);
        }

        return parent::loadUserByUsername($username);
    }

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \SprykerShop\Yves\CustomerPage\Security\Customer|\Symfony\Component\Security\Core\User\UserInterface
     */
    protected function loadUserBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer)
    {
        $customerTransfer = $this->ssoClient->getCustomerInformationBySsoAccessToken($ssoAccessTokenTransfer);
        $customerResponseTransfer = $this->getFactory()->getCustomerClient()->createCustomer($customerTransfer);

        return $this->getFactory()->createSecurityUser($customerResponseTransfer->getCustomerTransfer());
    }
}
