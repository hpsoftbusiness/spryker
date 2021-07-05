<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductAccessDeniedForUserNotLoggedInException extends AccessDeniedHttpException
{
}
