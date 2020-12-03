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
class SsoConsole extends Console
{
    public const COMMAND_NAME = 'sso:token:get';
    public const DESCRIPTION = 'This command will retrieve auth token by authorization code';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->addArgument('code', InputArgument::REQUIRED, 'SSO authorization code.');

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

        $messenger->info('Retrieving access token...');

        $ssoAccessTokenTransfer = $this->getFactory()->getSsoClient()->getAccessTokenByCode($input->getArgument('code'));

        $messenger->info(sprintf('Access Token: %s', $ssoAccessTokenTransfer->getAccessToken()));
        $messenger->info(sprintf('Token Type: %s', $ssoAccessTokenTransfer->getTokenType()));
        $messenger->info(sprintf('Access ID Token: %s', $ssoAccessTokenTransfer->getIdToken()));
        $messenger->info(sprintf('Expires in: %s', $ssoAccessTokenTransfer->getExpiresIn()));
        $messenger->info(sprintf('Refresh Token: %s', $ssoAccessTokenTransfer->getRefreshToken()));

        return static::CODE_SUCCESS;
    }
}
