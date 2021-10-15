<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\PostCustomer;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Client\Weclapp\Client\Customer\AbstractWeclappCustomerClient;

class PostCustomerClient extends AbstractWeclappCustomerClient implements PostCustomerClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/customer';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function postCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($customerTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getRequestBody(CustomerTransfer $customerTransfer): array
    {
        return $this->customerHydrator
            ->hydrateCustomerToWeclappCustomer($customerTransfer)
            ->toArray(true, true);
    }
}
