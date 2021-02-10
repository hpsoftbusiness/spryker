<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductDataImport;

use Pyz\Service\ProductDataImport\Plugin\Twig\Filters\JsonDecoder;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ProductDataImportServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pyz\Service\ProductDataImport\Plugin\Twig\Filters\JsonDecoder
     */
    public function createJsonDecoder(): JsonDecoder
    {
        return new JsonDecoder();
    }
}
