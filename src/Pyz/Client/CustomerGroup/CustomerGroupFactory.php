<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroup;

use Pyz\Client\CustomerGroup\Zed\CustomerGroupStub;
use Pyz\Client\CustomerGroup\Zed\CustomerGroupStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class CustomerGroupFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\CustomerGroup\Zed\CustomerGroupStubInterface
     */
    public function createZedCustomerGroupStub(): CustomerGroupStubInterface
    {
        return new CustomerGroupStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::SERVICE_ZED);
    }
}
