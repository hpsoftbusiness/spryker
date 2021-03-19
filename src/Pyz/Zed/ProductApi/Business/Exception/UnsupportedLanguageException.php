<?php

namespace Pyz\Zed\ProductApi\Business\Exception;

use Exception;
use Spryker\Zed\Api\ApiConfig;
use Throwable;

class UnsupportedLanguageException extends Exception
{
    public function __construct(
        $message = 'Unsupported X-Spryker-Language',
        $code = ApiConfig::HTTP_CODE_VALIDATION_ERRORS,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
