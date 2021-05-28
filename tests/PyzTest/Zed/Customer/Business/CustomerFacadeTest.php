<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Customer\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group Test
 * @group CustomerFacadeTest
 */
class CustomerFacadeTest extends Unit
{
    public const CUSTOMER_INCORRECT_EMAIL = 'some_incorrect_email';

    /**
     * @var \PyzTest\Zed\Customer\CustomerBusinessTester $tester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRegistrationCustomerEmailValidationFail(): void
    {
        // Arrange
        $customer = new CustomerBuilder([
            CustomerTransfer::EMAIL => self::CUSTOMER_INCORRECT_EMAIL,
        ]);

        // Act
        $customerResponse = $this->tester->getLocator()->customer()->facade()->registerCustomer($customer->build());

        // Assert
        $this->assertFalse($customerResponse->getIsSuccess());
        $this->assertNull($customerResponse->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testCreateCustomerEmailValidationFail(): void
    {
        // Arrange
        $customer = new CustomerBuilder([
            CustomerTransfer::EMAIL => self::CUSTOMER_INCORRECT_EMAIL,
        ]);

        // Act
        $customerResponse = $this->tester->getLocator()->customer()->facade()->addCustomer($customer->build());

        // Assert
        $this->assertFalse($customerResponse->getIsSuccess());
        $this->assertNull($customerResponse->getCustomerTransfer());
    }
}
