<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\DataImport\Persistence\DataImportRepositoryInterface getRepository()
 * @method \Pyz\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 */
class UserPermissionImport extends Console
{
    private const COMMAND = 'data:import:user-permissions';
    private const DESCRIPTION = 'Import user permissions';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getRepository()->createUserPermissions();

        return static::CODE_SUCCESS;
    }
}
