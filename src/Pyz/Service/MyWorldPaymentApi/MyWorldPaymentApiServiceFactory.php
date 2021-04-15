<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\MyWorldPaymentApi;

use Pyz\Service\MyWorldPaymentApi\Business\Model\AuthorizationHeaderGenerator;
use Spryker\Service\Kernel\AbstractServiceFactory;

class MyWorldPaymentApiServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pyz\Service\MyWorldPaymentApi\Business\Model\AuthorizationHeaderGenerator
     */
    public function createAuthorizationHeaderGenerator(): AuthorizationHeaderGenerator
    {
        return new AuthorizationHeaderGenerator();
    }
}
