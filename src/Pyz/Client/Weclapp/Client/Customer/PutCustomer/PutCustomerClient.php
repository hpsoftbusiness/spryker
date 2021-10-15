<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\PutCustomer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;
use Pyz\Client\Weclapp\Client\Customer\AbstractWeclappCustomerClient;

class PutCustomerClient extends AbstractWeclappCustomerClient implements PutCustomerClientInterface
{
    protected const REQUEST_METHOD = 'PUT';
    protected const ACTION_URL = '/customer/id/%s';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer $weclappCustomerTransfer
     *
     * @return void
     */
    public function putCustomer(
        CustomerTransfer $customerTransfer,
        WeclappCustomerTransfer $weclappCustomerTransfer
    ): void {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappCustomerTransfer),
            $this->getRequestBody($customerTransfer, $weclappCustomerTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer $weclappCustomerTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappCustomerTransfer $weclappCustomerTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappCustomerTransfer->getId());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer $weclappCustomerTransfer
     *
     * @return array
     */
    protected function getRequestBody(
        CustomerTransfer $customerTransfer,
        WeclappCustomerTransfer $weclappCustomerTransfer
    ): array {
        return $this->customerHydrator
            ->hydrateCustomerToWeclappCustomer($customerTransfer, $weclappCustomerTransfer)
            ->toArray(true, true);
    }
}
