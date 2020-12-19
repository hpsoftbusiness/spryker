<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Adyen;

use SprykerEco\Shared\Adyen\AdyenConstants as SprykerEcoAdyenConstants;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AdyenConstants extends SprykerEcoAdyenConstants
{
    public const SPLIT_ACCOUNT = 'ADYEN:SPLIT_ACCOUNT';
    public const SPLIT_ACCOUNT_COMMISSION_INTEREST = 'ADYEN:SPLIT_ACCOUNT_COMMISSION_INTEREST';
}
