<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Business\Anonymizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer as SprykerCustomerAnonymizer;

class CustomerAnonymizer extends SprykerCustomerAnonymizer
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function anonymizeCustomer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = parent::anonymizeCustomer($customerTransfer);
        $customerTransfer->setMyWorldCustomerId($this->generateRandomMyWorldCustomerId())
            ->setCustomerType(null)
            ->setIsActive(false)
            ->setCountryId(null)
            ->setCardNumber($this->generateRandomCardNumber())
            ->setMyWorldCustomerNumber(null);

        return $customerTransfer;
    }

    /**
     * @return string
     */
    protected function generateRandomCardNumber(): string
    {
        do {
            $randomMyWorldCustomerId = strtolower(md5((string)random_int(0, PHP_INT_MAX)));
        } while ($this->queryContainer->queryCustomers()->filterByCardNumber($randomMyWorldCustomerId)->exists());

        return $randomMyWorldCustomerId;
    }

    /**
     * @return string
     */
    protected function generateRandomMyWorldCustomerId(): string
    {
        do {
            $randomMyWorldCustomerId = strtolower(md5((string)random_int(0, PHP_INT_MAX)));
        } while ($this->queryContainer->queryCustomers()->filterByMyWorldCustomerId($randomMyWorldCustomerId)->exists());

        return $randomMyWorldCustomerId;
    }
}
