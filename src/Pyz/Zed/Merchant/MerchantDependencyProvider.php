<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Merchant;

use Spryker\Zed\Merchant\MerchantDependencyProvider as SprykerMerchantDependencyProvider;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileExpanderPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileMerchantPostCreatePlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileMerchantPostUpdatePlugin;
use Spryker\Zed\MerchantUser\Communication\Plugin\Merchant\SyncMerchantUsersStatusMerchantPostUpdatePlugin;

class MerchantDependencyProvider extends SprykerMerchantDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface[]
     */
    protected function getMerchantPostCreatePlugins(): array
    {
        return [
            new MerchantProfileMerchantPostCreatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[]
     */
    protected function getMerchantPostUpdatePlugins(): array
    {
        return [
            new MerchantProfileMerchantPostUpdatePlugin(),
            new SyncMerchantUsersStatusMerchantPostUpdatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface[]
     */
    protected function getMerchantExpanderPlugins(): array
    {
        return [
            new MerchantProfileExpanderPlugin(),
        ];
    }
}
