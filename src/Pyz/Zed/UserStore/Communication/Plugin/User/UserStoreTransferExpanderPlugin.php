<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\Plugin\User;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface;

/**
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 * @method \Pyz\Zed\UserStore\Communication\UserStoreCommunicationFactory getFactory()
 */
class UserStoreTransferExpanderPlugin extends AbstractPlugin implements UserTransferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expand UserTransfer with Store Id and Store Name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransfer(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getFacade()->expandUserTransferWithStore($userTransfer);
    }
}
