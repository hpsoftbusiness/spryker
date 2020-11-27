<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\UserChecker;

use SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException;
use SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerConfirmationUserChecker extends UserChecker
{
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException
     *
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof CustomerUserInterface) {
            return;
        }

        parent::checkPreAuth($user);

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
