<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
class UnsupportedResourceException extends BadRequestHttpException
{
    public const MESSAGE = 'There is no such resource: "%s"';

    /**
     * @param string|null $resource
     */
    public function __construct(?string $resource)
    {
        parent::__construct($this->generateMessage($resource), $this->getPrevious(), $this->code, $this->getHeaders());
    }

    /**
     * @param string|null $resource
     *
     * @return string
     */
    private function generateMessage(?string $resource): string
    {
        return sprintf(self::MESSAGE, $resource);
    }
}
