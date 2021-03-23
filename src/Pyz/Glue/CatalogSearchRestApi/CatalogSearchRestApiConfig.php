<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi;

use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig as SprykerCatalogSearchRestApiConfig;
use Spryker\Shared\Application\ApplicationConstants;

class CatalogSearchRestApiConfig extends SprykerCatalogSearchRestApiConfig
{
    /**
     * @return string|null
     */
    public function getYvesHost(): ?string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }
}
