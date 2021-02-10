<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Exception;

use Exception;

class RemovedProductHasOrderException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'We can not remove this product for it, we have the order';
}
