<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\User\Communication;

use Pyz\Zed\User\Communication\Table\UsersTable;
use Spryker\Zed\User\Communication\UserCommunicationFactory as SprykerUserCommunicationFactory;
use Spryker\Zed\User\UserDependencyProvider;

class UserCommunicationFactory extends SprykerUserCommunicationFactory
{
    /**
     * @return \Spryker\Zed\User\Communication\Table\UsersTable
     */
    public function createUserTable()
    {
        return new UsersTable(
            $this->getQueryContainer(),
            $this->getProvidedDependency(UserDependencyProvider::SERVICE_DATE_FORMATTER),
            $this->createUserTablePluginExecutor(),
            $this->getFacade()
        );
    }
}
