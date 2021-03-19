<?php

namespace Pyz\Zed\Api\Business\Exception;

class XSprykerApiKeyException extends AuthException
{
    /**
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param \Exception|null $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = 'Wrong X-Spryker-API-Key', $code = 401, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
