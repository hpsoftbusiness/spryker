<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage;

use Codeception\Actor;
use PyzTest\Yves\CheckoutPage\Traits\QuoteTransferBuilderTrait;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CheckoutPageProcessTester extends Actor
{
    use _generated\CheckoutPageProcessTesterActions;
    use QuoteTransferBuilderTrait;

    public const PAYMENT_SESSION_ID = '53aab886-1e41-448e-9cb9-61ec69146350';
}
