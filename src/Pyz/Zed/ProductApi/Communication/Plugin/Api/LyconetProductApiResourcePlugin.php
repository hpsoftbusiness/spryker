<?php

namespace Pyz\Zed\ProductApi\Communication\Plugin\Api;

use Pyz\Zed\ProductApi\ProductApiConfig;

class LyconetProductApiResourcePlugin extends AbstractProductApiResourcePlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName()
    {
        return ProductApiConfig::RESOURCE_LYCONET;
    }
}
