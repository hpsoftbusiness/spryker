<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Business;

use Pyz\Zed\UserStore\Business\UserExpander\UserExpander;
use Pyz\Zed\UserStore\UserStoreDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class UserStoreBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(UserStoreDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Pyz\Zed\UserStore\Business\UserExpander\UserExpander
     */
    public function createUserExpander(): UserExpander
    {
        return new UserExpander(
            $this->getStoreFacade()
        );
    }
}
