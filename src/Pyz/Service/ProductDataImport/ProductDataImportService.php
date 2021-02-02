<?php

namespace Pyz\Service\ProductDataImport;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Pyz\Service\ProductDataImport\ProductDataImportServiceFactory getFactory()
 */
class ProductDataImportService extends AbstractService implements ProductDataImportServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function jsonDecode(string $jsonString): array
    {
        return $this->getFactory()->createJsonDecoder()->jsonDecode($jsonString);
    }

}
