<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator;


interface ResponseValidatorInterface
{
    /**
     * @param array $response
     *
     * @return bool
     */
    public function validate(array $response): bool;
}
