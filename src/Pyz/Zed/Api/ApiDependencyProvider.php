<?php

namespace Pyz\Zed\Api;

use Pyz\Zed\ProductApi\Communication\Plugin\Api\BvDealsProductApiResourcePlugin;
use Pyz\Zed\ProductApi\Communication\Plugin\Api\RegularProductApiResourcePlugin;
use Pyz\Zed\ProductApi\Communication\Plugin\Api\SpDealsProductApiResourcePlugin;
use Spryker\Zed\Api\ApiDependencyProvider as SprykerApiDependencyProvider;

class ApiDependencyProvider extends SprykerApiDependencyProvider
{
    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected function getApiResourcePluginCollection(): array
    {
        return [
            new RegularProductApiResourcePlugin(),
            new BvDealsProductApiResourcePlugin(),
            new SpDealsProductApiResourcePlugin(),
        ];
    }
}
