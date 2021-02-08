<?php

namespace Pyz\Service\ProductDataImport;

interface ProductDataImportServiceInterface
{
    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function jsonDecode(string $jsonString): array;
}
