<?php

namespace Pyz\Service\ProductDataImport\Plugin\Twig\Filters;

class JsonDecoder
{
    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function jsonDecode(string $jsonString): array
    {
        return json_decode($jsonString, true);
    }
}
