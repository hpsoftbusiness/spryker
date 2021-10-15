<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface getRepository()
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 */
class WeclappInitialDataExportConsole extends Console
{
    public const COMMAND_NAME = 'weclapp:initial-data:export';
    public const DESCRIPTION = 'Export initial data to Weclapp';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $dependingCommands = $this->getDependingCommands();

        foreach ($dependingCommands as $commandName) {
            $this->runDependingCommand($commandName);

            if ($this->hasError()) {
                return $this->getLastExitCode();
            }
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return string[]
     */
    protected function getDependingCommands(): array
    {
        return [
            WeclappTaxRateExportConsole::COMMAND_NAME,
            WeclappCategoryExportConsole::COMMAND_NAME,
            WeclappProductExportConsole::COMMAND_NAME,
        ];
    }
}
