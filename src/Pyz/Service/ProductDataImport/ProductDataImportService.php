<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductDataImport;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Pyz\Service\ProductDataImport\ProductDataImportServiceFactory getFactory()
 */
class ProductDataImportService extends AbstractService implements ProductDataImportServiceInterface
{
    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function jsonDecode(string $jsonString): array
    {
        return $this->getFactory()->createJsonDecoder()->jsonDecode($jsonString);
    }
}
