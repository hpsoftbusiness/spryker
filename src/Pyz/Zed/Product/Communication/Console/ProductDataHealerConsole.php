<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Communication\Console;

use Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Pyz\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 */
class ProductDataHealerConsole extends Console
{
    private const COMMAND = 'data:heal';
    private const DESCRIPTION = 'Executes product data healers.';
    private const ARGUMENT_HEALERS = 'healers';
    private const ARGUMENT_HEALERS_DESCRIPTION = 'List of healers to execute separated by commas.';
    private const ARGUMENT_HEALERS_SEPARATOR = ',';

    /**
     * @var \Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface[]
     */
    private $productDataHealers;

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->productDataHealers = $this->getMappedProductHealerPlugins();
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addUsage($this->getPluginUsageText());

        $this->addArgument(
            self::ARGUMENT_HEALERS,
            InputArgument::REQUIRED,
            self::ARGUMENT_HEALERS_DESCRIPTION
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $healerNames = $this->getHealerListFromArgument($input);
        $messenger = $this->getMessenger();
        if (empty($healerNames)) {
            $messenger->error('No product data healer plugins to execute.');
        }

        foreach ($healerNames as $healerName) {
            $productDataHealerPlugin = $this->getProductDataHealerPluginByName($healerName);
            if (!$productDataHealerPlugin) {
                $messenger->warning('Product healer plugin with name \'' . $healerName . '\' was not found.');
                continue;
            }

            $productDataHealerPlugin->execute($messenger);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $name
     *
     * @return \Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface|null
     */
    private function getProductDataHealerPluginByName(string $name): ?ProductDataHealerPluginInterface
    {
        return $this->productDataHealers[$name] ?? null;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string[]
     */
    private function getHealerListFromArgument(InputInterface $input): array
    {
        $healersArgumentValue = $input->getArgument(self::ARGUMENT_HEALERS);
        if (!$healersArgumentValue) {
            return [];
        }

        return explode(self::ARGUMENT_HEALERS_SEPARATOR, $healersArgumentValue);
    }

    /**
     * @return \Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface[]
     */
    private function getMappedProductHealerPlugins(): array
    {
        $plugins = [];

        foreach ($this->getFactory()->getProductDataHealerPlugins() as $productDataHealerPlugin) {
            $plugins[$productDataHealerPlugin->getName()] = $productDataHealerPlugin;
        }

        return $plugins;
    }

    /**
     * @return string
     */
    private function getPluginUsageText(): string
    {
        $availablePluginNames = array_keys($this->productDataHealers);

        return sprintf(
            "[\n\t%s\n]",
            implode(",\n\t", $availablePluginNames)
        );
    }
}
