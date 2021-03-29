<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Console;

use Generator;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductAbstractPageSearchRefreshConsole extends Console
{
    /**
     * List of environments that can execute this command without force flag.
     */
    private const ALLOWED_ENVIRONMENTS = [
        'development',
        'docker.dev',
        'devtest',
    ];

    public const COMMAND_NAME = 'search:refresh-data:abstract';
    public const DESCRIPTION = 'Refreshes the whole search index data';

    private const CHUNK_SIZE = 50;

    private const OPTION_CATEGORY_ID = 'cid';
    private const OPTION_FORCE_FLAG = 'force';
    private const OPTION_FORCE_FLAG_SHORTCUT = '-f';
    private const OPTION_FORCE_FLAG_DESCRIPTION = 'Forces search data refresh regardless of environment.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addOption(
                self::OPTION_CATEGORY_ID,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set category id whose product data should be resynchronized.
                Usage:
                - For synchronizing product abstract data of one category --cid parameter with category id, e.g. \'--cid=744\'.
                - For multiple categories provide --cid parameter with list of category ids separated by spaces, e.g. \'--cid="744 745 756"\'.
                - If --cid parameter is missing, all abstract products will be synchronized.'
            )
            ->addOption(
                self::OPTION_FORCE_FLAG,
                self::OPTION_FORCE_FLAG_SHORTCUT,
                InputOption::VALUE_NONE,
                self::OPTION_FORCE_FLAG_DESCRIPTION
            );
    }

    /**
     * Usage:
     * - For synchronizing product abstract data of one category --cid parameter with category id, e.g. '--cid=10'.
     * - For multiple categories provide --cid parameter with list of category ids separated by spaces, e.g. '--cid="10 11 15"'.
     * - If --cid parameter is missing, all abstract products will be synchronized.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messenger = $this->getMessenger();
        $forceFlag = $this->getForceFlag($input);
        $allowedToExecute = $this->isAllowedToExecuteForEnvironment($forceFlag);
        if (!$allowedToExecute) {
            $messenger->warning('Command is not allowed to execute on this environment. If you are absolutely sure you need to execute it, please add -f flag.');

            return static::CODE_SUCCESS;
        }

        $categoryIds = $this->getCategoryIds($input);
        $processed = 0;

        foreach ($this->getAbstractIdChunk($categoryIds) as $productAbstractIds) {
            $messenger->info("Processing products {$processed} ...");
            $this->getFacade()->refresh($productAbstractIds);
            $processed += count($productAbstractIds);
        }

        $messenger->info("Done. Processed {$processed} items.");

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return int[]
     */
    private function getCategoryIds(InputInterface $input): array
    {
        $categoryIds = $input->getOption(self::OPTION_CATEGORY_ID) ?? [];

        if (is_array($categoryIds) && empty($categoryIds)) {
            return [];
        }

        $categoryIds = explode(' ', $categoryIds);

        return array_map('intval', $categoryIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return \Generator
     */
    private function getAbstractIdChunk(array $categoryIds): Generator
    {
        $query = SpyProductAbstractQuery::create()
            ->select(['id_product_abstract']);

        if (!empty($categoryIds)) {
            $query->useSpyProductCategoryQuery()
                ->filterByFkCategory_In($categoryIds)
                ->endUse();
        }
        $query
            ->innerJoinSpyProductCategory()
            ->useSpyProductQuery()
            ->filterByIsActive(true)
            ->endUse()
            ->groupByIdProductAbstract();

        $results = $query->find()->getData();
        $iteration = 0;
        $chunk = [];

        foreach ($results as $idProductAbstract) {
            ++$iteration;
            $chunk[] = $idProductAbstract;

            if ($iteration % self::CHUNK_SIZE === 0) {
                yield $chunk;

                $chunk = [];
            }
        }

        if (count($chunk)) {
            yield $chunk;
        }
    }

    /**
     * @param bool $forceFlag
     *
     * @return bool
     */
    private function isAllowedToExecuteForEnvironment(bool $forceFlag): bool
    {
        $environment = getenv('APPLICATION_ENV');
        if ($environment === false) {
            $this->getMessenger()->warning('Application environment variable is not set.');

            return false;
        }

        return in_array(strtolower($environment), self::ALLOWED_ENVIRONMENTS) || $forceFlag;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    private function getForceFlag(InputInterface $input): bool
    {
        return (bool)$input->getOption(self::OPTION_FORCE_FLAG);
    }
}
