<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Plugin\Console;

use DateTime;
use Exception;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()()
 */
class CustomerSprykerCreateConsole extends Console
{
    private const COMMAND_NAME = 'customer:create';
    private const DESCRIPTION = 'Creates Spryker customer for testing the website without a need to authenticate through MyWorld service.';

    private const OPTION_EMAIL = 'email';
    private const OPTION_EMAIL_DESCRIPTION = 'Customer email.';

    private const OPTION_PASSWORD = 'password';
    private const OPTION_PASSWORD_DESCRIPTION = 'Customer password. If left black, default "password" value will be used.';

    private const DEFAULT_PASSWORD = 'password';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION)
            ->addOption(self::OPTION_EMAIL, null, InputOption::VALUE_REQUIRED, self::OPTION_EMAIL_DESCRIPTION)
            ->addOption(self::OPTION_PASSWORD, null, InputOption::VALUE_OPTIONAL, self::OPTION_PASSWORD_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->getFactory()->getConfig()->isSsoLoginEnabled()) {
            $output->writeln('<error>This command should be executed ONLY in local environment. Please disable SSO login.</error>');

            return self::CODE_ERROR;
        }

        $email = $this->getCustomerEmail($input);
        $password = $this->getCustomerPassword($input);
        $customerTransfer = $this->createCustomerTransfer($email, $password);

        try {
            $this->getFacade()->addCustomer($customerTransfer);
            $output->writeln(sprintf('<info>Customer was created with email <%s> and password <%s>.</info>', $email, $password));
        } catch (Exception $exception) {
            $output->writeln('<error>Error occurred while trying to create a customer.</error>');

            throw $exception;
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    private function createCustomerTransfer(string $email, string $password): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setEmail($email)
            ->setNewPassword($password)
            ->setPassword($password)
            ->setRegistered((new DateTime())->format('Y-m-d'))
            ->setMyWorldCustomerId($this->generateMyWorldCustomerId())
            ->setCustomerType(SpyCustomerTableMap::COL_CUSTOMER_TYPE_CUSTOMER)
            ->setIsActive(true);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    private function getCustomerEmail(InputInterface $input): string
    {
        return $input->getOption(self::OPTION_EMAIL);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    private function getCustomerPassword(InputInterface $input): string
    {
        return $input->getOption(self::OPTION_PASSWORD) ?? self::DEFAULT_PASSWORD;
    }

    /**
     * @return string
     */
    private function generateMyWorldCustomerId(): string
    {
        return md5((string)time());
    }
}
