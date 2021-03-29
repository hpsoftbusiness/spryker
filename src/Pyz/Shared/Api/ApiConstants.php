<?php

namespace Pyz\Shared\Api;

use Spryker\Shared\Api\ApiConstants as SprykerApiConstants;

interface ApiConstants extends SprykerApiConstants
{
    public const X_SPRYKER_API_KEY = 'X-SPRYKER-API-KEY';

    public const TRANSFORMER_STANDARD = 'TRANSFORMER_STANDARD';

    public const TRANSFORMER_SIMPLE = 'TRANSFORMER_SIMPLE';

    public const API_REQUEST_TRANSFER_PARAM_KEY = 'api_request_transfer_key';
}
