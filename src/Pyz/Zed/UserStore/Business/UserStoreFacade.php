<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\UserStore\Business\UserStoreBusinessFactory getFactory()
 */
class UserStoreFacade extends AbstractFacade implements UserStoreFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithStore(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFactory()
            ->createUserExpander()
            ->expandUserTransferWithStore($userTransfer);
    }
}
