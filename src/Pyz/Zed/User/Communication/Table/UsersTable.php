<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\User\Communication\Table;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Spryker\Zed\User\Communication\Table\PluginExecutor\UserTablePluginExecutorInterface;
use Spryker\Zed\User\Communication\Table\UsersTable as SprykerUsersTable;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;

class UsersTable extends SprykerUsersTable
{
    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $userQueryContainer
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\User\Communication\Table\PluginExecutor\UserTablePluginExecutorInterface $userTablePluginExecutor
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct(
        UserQueryContainerInterface $userQueryContainer,
        UtilDateTimeServiceInterface $utilDateTimeService,
        UserTablePluginExecutorInterface $userTablePluginExecutor,
        UserFacadeInterface $userFacade
    ) {
        parent::__construct($userQueryContainer, $utilDateTimeService, $userTablePluginExecutor);
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $userQuery = $this->userQueryContainer->queryUser();
        $userTransfer = $this->userFacade->getCurrentUser();
        if ($userTransfer->getFkStore()) {
            $userQuery->filterByFkStore($userTransfer->getFkStore());
        }
        $queryResults = $this->runQuery($userQuery, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = array_merge([
                SpyUserTableMap::COL_USERNAME => $item[SpyUserTableMap::COL_USERNAME],
                SpyUserTableMap::COL_FIRST_NAME => $item[SpyUserTableMap::COL_FIRST_NAME],
                SpyUserTableMap::COL_LAST_NAME => $item[SpyUserTableMap::COL_LAST_NAME],
                SpyUserTableMap::COL_LAST_LOGIN => $this->getLastLoginDateTime($item),
                SpyUserTableMap::COL_STATUS => $this->createStatusLabel($item),
                self::ACTION => implode(' ', $this->createActionButtons($item)),
            ], $this->executeDataExpanderPlugins($item));
        }

        return $results;
    }
}
