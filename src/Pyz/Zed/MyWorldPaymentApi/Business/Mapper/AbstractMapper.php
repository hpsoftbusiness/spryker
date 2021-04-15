<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;

abstract class AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return void
     */
    abstract protected function validateRequestTransfer(MyWorldApiRequestTransfer $requestTransfer): void;

    /**
     * @param array $data
     *
     * @return array
     */
    protected function removeRedundantParams(array $data): array
    {
        $data = array_filter($data, function ($item) {
            if ($item instanceof ArrayObject) {
                return $item->count() !== 0;
            }

            return !empty($item);
        });

        foreach ($data as $key => $value) {
            if (is_array($value) || $value instanceof ArrayObject) {
                $data[$key] = $this->removeRedundantParams($value);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function capitalizeArrayKeys(array $data): array
    {
        return array_combine(
            array_map(function ($key) {
                return ucfirst($key);
            }, array_keys($data)),
            array_values($data)
        );
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function capitalizeArrayKeysRecursive(array $data): array
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                $item = $this->capitalizeArrayKeysRecursive($item);
            }

            return $item;
        }, $this->capitalizeArrayKeys($data));
    }

    /**
     * @param array $requestArray
     *
     * @return array
     */
    protected function normalizeArrayKeys(array $requestArray): array
    {
        return array_combine(
            array_map(function ($key) {
                if ($key === 'SessionId') {
                    return 'SessionID';
                }

                if ($key === 'CurrencyId') {
                    return 'CurrencyID';
                }

                if ($key === 'PaymentId') {
                    return 'PaymentID';
                }

                return $key;
            }, array_keys($requestArray)),
            array_values($requestArray)
        );
    }
}
