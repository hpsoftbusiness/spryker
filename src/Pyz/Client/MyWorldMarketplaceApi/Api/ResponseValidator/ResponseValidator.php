<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator;

class ResponseValidator implements ResponseValidatorInterface
{
    protected const FIELD_RESULT_CODE = 'ResultCode';
    protected const FIELD_RESULT_DESCRIPTION = 'ResultDescription';
    protected const FIELD_DATA = 'Data';

    /**
     * @param array $response
     *
     * @return bool
     */
    public function validate(array $response): bool
    {
        $isValid = $this->validateMandatoryFields($response);

        if ($isValid) {
            $isValid = $this->validateResultCode($response);
        }

        return $isValid;
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function validateMandatoryFields(array $response): bool
    {
        return isset($response[static::FIELD_RESULT_CODE])
            && isset($response[static::FIELD_RESULT_DESCRIPTION])
            && key_exists(static::FIELD_DATA, $response);
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function validateResultCode(array $response): bool
    {
        return $response[static::FIELD_RESULT_CODE] === 0;
    }
}
