<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Sso;

use Pyz\Client\Customer\CustomerClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class SsoFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SsoDependencyProvider::CLIENT_CUSTOMER);
    }
}
