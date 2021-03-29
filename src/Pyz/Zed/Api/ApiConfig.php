<?php

namespace Pyz\Zed\Api;

use Spryker\Zed\Api\ApiConfig as SprykerApiConfig;

class ApiConfig extends SprykerApiConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isApiEnabled(): bool
    {
        return true;
    }
}
