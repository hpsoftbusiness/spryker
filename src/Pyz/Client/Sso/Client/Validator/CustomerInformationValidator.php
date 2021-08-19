<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client\Validator;

class CustomerInformationValidator implements CustomerInformationValidatorInterface
{
    protected const KEY_DATA = 'Data';
    protected const CUSTOMER_INFORMATION_KEYS = [
        'Email',
        'CustomerID',
        'CustomerNumber',
        'Firstname',
        'Lastname',
        'BirthdayDate',
        'MobilePhoneNumber',
        'Status',
        'CustomerType',
    ];

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data): bool
    {
        foreach (static::CUSTOMER_INFORMATION_KEYS as $key) {
            /** @var array $keyDataArray */
            $keyDataArray = $data[static::KEY_DATA] ?? [];

            if (!array_key_exists($key, $keyDataArray)) {
                return false;
            }
        }

        return true;
    }
}
