<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber;

use Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer;
use Generated\Shared\Transfer\CustomerInformationByCustomerNumberResponseTransfer;

interface CustomerInformationByCustomerNumberInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerInformationByCustomerNumberResponseTransfer
     */
    public function getCustomerInformationByCustomerNumber(CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer): CustomerInformationByCustomerNumberResponseTransfer;
}
