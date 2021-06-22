<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen;

use Codeception\Actor;
use Pyz\Zed\Adyen\AdyenConfig;

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
class AdyenCommunicationTester extends Actor
{
    use _generated\AdyenCommunicationTesterActions;

    /**
     * @return \Pyz\Zed\Adyen\AdyenConfig
     */
    public function getConfig(): AdyenConfig
    {
        return new AdyenConfig();
    }
}
