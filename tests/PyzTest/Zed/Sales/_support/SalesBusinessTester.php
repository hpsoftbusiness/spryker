<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Sales;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;

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
class SalesBusinessTester extends Actor
{
    use _generated\SalesBusinessTesterActions;

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function buildItemTransfer(array $overrideData): ItemTransfer
    {
        return (new ItemBuilder($overrideData))->build();
    }
}
