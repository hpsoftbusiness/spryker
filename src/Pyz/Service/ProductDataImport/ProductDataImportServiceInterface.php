<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductDataImport;

interface ProductDataImportServiceInterface
{
    /**
     * Function jsonDecode for twig
     *
     * @api
     *
     * @param string $jsonString
     *
     * @return array
     */
    public function jsonDecode(string $jsonString): array;
}
