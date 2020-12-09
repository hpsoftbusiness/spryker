<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiFacade getFacade()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Communication\MyWorldMarketplaceApiCommunicationFactory getFactory()
 */
class MyWorldMarketplaceApiConsole extends Console
{
    public const COMMAND_NAME = 'some:command';
    public const DESCRIPTION = 'Describe me!';

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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messenger = $this->getMessenger();

        $result = $this->getFacade()->test();

        $messenger->info(sprintf(
            'You just executed %s!',
            static::COMMAND_NAME
        ));

        return static::CODE_SUCCESS;
    }
}
