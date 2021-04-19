<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business\CTE;

trait MariaDbDataFormatterTrait
{
    /**
     * @param array $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatStringList(array $values, ?int $minimumLength = null): string
    {
        if ($minimumLength > 0 && count($values) < $minimumLength) {
            $values = array_pad($values, $minimumLength, '');
        }

        if ($values === []) {
            return '';
        }

        $values = array_map(function ($value) {
            return $value ?? '';
        }, $values);

        return implode(',', $values);
    }

    /**
     * @param array $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatDataStringList(array $values, ?int $minimumLength = null): string
    {
        if ($minimumLength > 0 && count($values) < $minimumLength) {
            $values = array_pad($values, $minimumLength, '');
        }

        if ($values === []) {
            return '';
        }

        $values = array_map(function ($value) {
            return str_replace(',', '|', $value) ?? '';
        }, $values);

        return implode(',', $values);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function collectMultiInsertData(array $data): string
    {
        $queryParameters = '';

        foreach ($data as $key => $itemData) {
            $stringParam = implode(",", array_map(function ($param) {
                if (is_string($param)) {
                    return "'$param'";
                }

                return $param;
            }, $itemData));
            $queryParameters .= "(" . $stringParam . ")";

            if (next($data)) {
                $queryParameters .= ',';
            }
        }

        return $queryParameters;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function collectMultiInsertDataForLocalizedAttributes(array $data): string
    {
        $queryParameters = '';

        foreach ($data as $localizedData) {
            if ($queryParameters !== '') {
                $queryParameters .= ',';
            }
            $queryParameters .= $this->collectMultiInsertData($localizedData);
        }

        return $queryParameters;
    }
}
