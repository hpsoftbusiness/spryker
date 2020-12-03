<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Communication\Console;


use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\Sso\Communication\SsoCommunicationFactory getFactory()
 */
class SsoAuthConsole extends Console
{
    public const COMMAND_NAME = 'sso:code:get';
    public const DESCRIPTION = 'This command will retrieve an authorization code';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messenger = $this->getMessenger();

        $messenger->info($this->getFactory()->getSsoClient()->getAuthorizeUrl('en_US'));

        return static::CODE_SUCCESS;
    }
}
