<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\UserChecker;

use Pyz\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException;
use SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface;
use SprykerShop\Yves\CustomerPage\UserChecker\CustomerConfirmationUserChecker as SprykerCustomerConfirmationUserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerConfirmationUserChecker extends SprykerCustomerConfirmationUserChecker
{
    /**
     * @var \Pyz\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

    /**
     * @param \Pyz\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     */
    public function __construct(CustomerPageConfig $customerPageConfig)
    {
        $this->customerPageConfig = $customerPageConfig;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException
     *
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$this->customerPageConfig->isSsoLoginEnabled()) {
            parent::checkPreAuth($user);

            return;
        }

        if (!$user instanceof CustomerUserInterface) {
            return;
        }

        $customerTransfer = $user->getCustomerTransfer();
        if ($customerTransfer->getIsActive() === false
            || (!$customerTransfer->getMyWorldCustomerId() && $customerTransfer->getRegistered() === null)
        ) {
            $ex = new NotConfirmedAccountException();
            $ex->setUser($user);

            throw $ex;
        }
    }
}
