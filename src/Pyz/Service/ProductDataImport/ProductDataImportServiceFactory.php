<?php

namespace Pyz\Service\ProductDataImport;

use Pyz\Service\ProductDataImport\Plugin\Twig\Filters\JsonDecoder;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ProductDataImportServiceFactory extends AbstractServiceFactory
{
    /**
     * @return JsonDecoder
     */
    public function createJsonDecoder(): JsonDecoder
    {
        return new JsonDecoder();
    }
}
