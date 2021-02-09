<?php

namespace Pyz\Zed\Product\Business\Exception;

use Exception;

class RemovedProductHasOrderException extends Exception
{
    protected $message = 'We can not remove this product for it, we have the order';
}
