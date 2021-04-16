<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Exception;

use Exception;
use Spryker\Zed\Api\ApiConfig;
use Throwable;

class UnsupportedLanguageException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        $message = 'Unsupported X-Spryker-Language',
        $code = ApiConfig::HTTP_CODE_VALIDATION_ERRORS,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
