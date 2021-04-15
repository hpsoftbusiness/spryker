<?php declare(strict_types=1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\MyWorldPayment;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class MyWorldPaymentConfig extends AbstractSharedConfig
{
    /**
     * Payment provider key used for all MyWorld PSP payment methods.
     */
    public const PAYMENT_PROVIDER_NAME_MY_WORLD = 'myWorld';
}
