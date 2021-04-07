<?php

namespace Pyz\Zed\ProductApi\Communication\Plugin\Api;

use Pyz\Zed\ProductApi\ProductApiConfig;

class OneSenseProductApiResourcePlugin extends AbstractProductApiResourcePlugin
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
        return ProductApiConfig::RESOURCE_ONE_SENSE;
    }
}
