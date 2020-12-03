<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Pyz\Client\Sso\SsoClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider as SprykerCustomerUserProvider;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerUserProvider extends SprykerCustomerUserProvider
{
    use LoggerTrait;

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
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     *
     * @return \SprykerShop\Yves\CustomerPage\Security\Customer|\Symfony\Component\Security\Core\User\UserInterface
     */
    protected function loadUserBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer)
    {
        $customerTransfer = $this->ssoClient->getCustomerInformationBySsoAccessToken($ssoAccessTokenTransfer);

        if (!$customerTransfer->getMyWorldCustomerId()) {
            throw new AuthenticationException();
        }

        $loadedCustomerTransfer = $this->loadCustomerByMyWorldCustomerId($customerTransfer->getMyWorldCustomerId());

        if ($loadedCustomerTransfer->getIdCustomer() === null) {
            $this->getLogger()->error(__CLASS__ . ' SSO: USER NOT FOUND, CREATING...');
            $customerResponseTransfer = $this->getFactory()->getCustomerClient()->createCustomer($customerTransfer);
            $this->getLogger()->error(__CLASS__ . ' SSO: USER CREATED');
            $loadedCustomerTransfer = $customerResponseTransfer->getCustomerTransfer();
        }

        $loadedCustomerTransfer->setSsoAccessToken($ssoAccessTokenTransfer);
        $this->getLogger()->error(__CLASS__ . ' SSO: CREATING SECURITY USER');
        return $this->getFactory()->createSecurityUser($loadedCustomerTransfer);
    }

    /**
     * @param string $myWorldCustomerId
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function loadCustomerByMyWorldCustomerId(string $myWorldCustomerId): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setMyWorldCustomerId($myWorldCustomerId);

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomerByEmail($customerTransfer);

        return $customerTransfer;
    }
}
