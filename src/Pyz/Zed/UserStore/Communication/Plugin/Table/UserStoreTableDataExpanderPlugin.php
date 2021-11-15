<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\Plugin\Table;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface;

/**
 * @method \Pyz\Zed\UserStore\Communication\UserStoreCommunicationFactory getFactory()
 * @method \Pyz\Zed\UserStore\Business\UserStoreFacadeInterface getFacade()
 */
class UserStoreTableDataExpanderPlugin extends AbstractPlugin implements UserTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return $this->getFactory()
            ->createUserStoreTableDataExpander()
            ->expandData($item);
    }
}
