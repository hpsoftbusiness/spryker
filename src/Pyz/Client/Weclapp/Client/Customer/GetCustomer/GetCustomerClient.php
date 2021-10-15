<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\GetCustomer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;
use Pyz\Client\Weclapp\Client\Customer\AbstractWeclappCustomerClient;

class GetCustomerClient extends AbstractWeclappCustomerClient implements GetCustomerClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/customer?referenceNumber-eq=%s';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer|null
     */
    public function getCustomer(CustomerTransfer $customerTransfer): ?WeclappCustomerTransfer
    {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($customerTransfer)
        );
        $weclappCustomerData = json_decode($weclappResponse->getBody()->__toString(), true)['result'][0]
            ?? null;
        if (!$weclappCustomerData) {
            return null;
        }

        return $this->customerHydrator->mapWeclappDataToWeclappCustomer($weclappCustomerData);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    protected function getActionUrl(CustomerTransfer $customerTransfer): string
    {
        return sprintf(static::ACTION_URL, $customerTransfer->getCustomerReference());
    }
}
