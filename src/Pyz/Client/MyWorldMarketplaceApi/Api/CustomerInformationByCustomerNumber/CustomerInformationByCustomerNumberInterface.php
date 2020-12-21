<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerInformationByCustomerNumberInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationByCustomerNumber(CustomerTransfer $customerTransfer): CustomerTransfer;
}
