<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\DataFormatter;

use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

class DataImportDataFormatter implements DataImportDataFormatterInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(DataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $value
     * @param string $replace
     *
     * @return string
     */
    public function replaceDoubleQuotes(string $value, string $replace = ''): string
    {
        return str_replace('"', $replace, $value);
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArray(array $values): string
    {
        if (!$values) {
            return '{null}';
        }

        $values = array_map(function ($value) {
            return ($value === null || $value === '') ? 'NULL' : $value;
        }, $values);

        return sprintf(
            '{%s}',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayString(array $values): string
    {
        return sprintf(
            '{"%s"}',
            pg_escape_string(implode('","', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayBoolean(array $values): string
    {
        $values = array_map(function ($value) {
            return $value ? 'true' : 'false';
        }, $values);

        return sprintf(
            '{%s}',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayFromJson(array $values): string
    {
        return sprintf(
            '[%s]',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @param array $collection
     * @param string $key
     *
     * @return array
     */
    public function getCollectionDataByKey(array $collection, string $key): array
    {
        return array_column($collection, $key);
    }

    /**
     * @param array $priceData
     *
     * @return string
     */
    public function formatPostgresPriceDataString(array $priceData): string
    {
        $priceData = array_map(function ($price) {
            return $price ?: null;
        }, $priceData);

        return pg_escape_string($this->utilEncodingService->encodeJson($priceData));
    }

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
    public function formatStringArrayList(array $values, ?int $minimumLength = null): string
    {
        if ($minimumLength > 0 && count($values) < $minimumLength) {
            $values = array_pad($values, $minimumLength, '{}');
        }

        if ($values === []) {
            return '';
        }

        $values = array_map(function ($value) {
            return $value ?? '';
        }, $values);

        return '[' . implode(',', $values) . ']';
    }

    /**
     * @param array $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatStringArrayListInQuote(array $values, ?int $minimumLength = null): string
    {
        if ($minimumLength > 0 && count($values) < $minimumLength) {
            $values = array_pad($values, $minimumLength, '');
        }

        if ($values === []) {
            return '';
        }

        $values = array_map(function ($value) {
            return '"' . $value . '"' ?? '';
        }, $values);

        return '[' . implode(',', $values) . ']';
    }

    /**
     * @param array $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatBooleanList(array $values, ?int $minimumLength = null): string
    {
        if ($minimumLength > 0 && count($values) < $minimumLength) {
            $values = array_pad($values, $minimumLength, 0);
        }

        if ($values === []) {
            return '';
        }

        $values = array_map(function ($value) {
            return $value ?: 0;
        }, $values);

        return implode(',', $values);
    }

    /**
     * @param array $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatPriceStringList(array $values, ?int $minimumLength = null): string
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
     * @param array $stringBoolean
     *
     * @return string
     */
    public function formatStringBooleanData(array $stringBoolean): string
    {
        $values = array_map(function ($value) {
            return $value === 'TRUE';
        }, $stringBoolean);

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
                if (is_bool($param)) {
                    return (int)$param;
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
