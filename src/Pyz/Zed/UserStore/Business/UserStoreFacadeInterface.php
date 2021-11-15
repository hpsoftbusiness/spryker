<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Business;

use Generated\Shared\Transfer\UserTransfer;

interface UserStoreFacadeInterface
{
    /**
     * Specification:
     * - Retrieve user store from storage and expands UserTransfer with Store Id and Store Name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithStore(UserTransfer $userTransfer): UserTransfer;
}
