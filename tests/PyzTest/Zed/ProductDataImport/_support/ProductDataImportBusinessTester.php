<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductDataImport;

use Codeception\Actor;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

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
class ProductDataImportBusinessTester extends Actor
{
    use _generated\ProductDataImportBusinessTesterActions;

    /**
     * @param \Symfony\Component\Console\Command\Command $mainCommand
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getCommandTester(Command $mainCommand): CommandTester
    {
        $application = new Application();
        $application->add($mainCommand);

        $mainCommand = $application->find($mainCommand->getName());

        return new CommandTester($mainCommand);
    }
}
