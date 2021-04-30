<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Communication\Console;

use Pyz\Zed\ProductDataImport\Communication\Console\ProductDataImportConsole;
use Spryker\Shared\Queue\QueueConfig;
use Spryker\Zed\Queue\Communication\Console\QueueWorkerConsole as SprykerQueueWorkerConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Queue\Communication\QueueCommunicationFactory getFactory()
 */
class QueueWorkerConsole extends SprykerQueueWorkerConsole
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->getFactory()->getStorageClient()->get(ProductDataImportConsole::DATA_IMPORT_KEY) !== null) {
            $output->writeln('<fg=red> Data Import in progress</>');

            return static::CODE_SUCCESS;
        }

        $options = [
            QueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => $input->getOption(static::OPTION_STOP_WHEN_EMPTY),
        ];

        $this->getFacade()->startWorker(self::QUEUE_RUNNER_COMMAND, $output, $options);

        return static::CODE_SUCCESS;
    }
}
