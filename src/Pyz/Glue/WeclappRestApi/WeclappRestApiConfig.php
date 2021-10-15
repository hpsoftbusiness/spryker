<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class WeclappRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_WEBHOOK = 'weclapp-webhook';

    public const WEBHOOK_ENTITY_NAME_WAREHOUSE_STOCK = 'warehouseStock';
    public const WEBHOOK_ENTITY_NAME_SHIPMENT = 'shipment';
}
