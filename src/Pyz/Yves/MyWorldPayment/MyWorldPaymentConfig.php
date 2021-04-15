<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class MyWorldPaymentConfig extends AbstractBundleConfig
{
    public const FORM_TEMPLATE_PATH = 'MyWorldPayment' . DIRECTORY_SEPARATOR . 'bonus';
    public const FORM_PROPERTY_PATH = 'MyWorldPaymentPropertyPath';
    public const FORM_NAME = 'myWorldUseEVoucherBalance';
    public const FORM_FIELD_BALANCE_NAME = 'myWorldUseEVoucherBalance';
    public const FORM_PROVIDER_NAME = 'MyWorldPaymentProviderName';
}
