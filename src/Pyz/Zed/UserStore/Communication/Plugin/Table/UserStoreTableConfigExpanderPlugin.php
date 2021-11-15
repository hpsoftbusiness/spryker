<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\Plugin\Table;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

/**
 * @method \Pyz\Zed\UserStore\Communication\UserStoreCommunicationFactory getFactory()
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 */
class UserStoreTableConfigExpanderPlugin extends AbstractPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createUserStoreTableConfigExpander()
            ->expandConfig($config);
    }
}
