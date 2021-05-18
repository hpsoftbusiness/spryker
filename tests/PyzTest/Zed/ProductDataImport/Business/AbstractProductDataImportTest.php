<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductDataImport\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImportInterface;
use Pyz\Zed\ProductDataImport\Communication\Console\ProductDataImportConsole;
use Pyz\Zed\ProductDataImport\ProductDataImportConfig;
use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Symfony\Component\Console\Command\Command;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductDataImport
 * @group Business
 * @group AbstractProductDataImportTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductDataImportTest extends Unit
{
    protected const BASE_TEST_DATA_DIR = __DIR__ . '/../_data/';
    protected const VIRTUAL_IMPORT_FILE_DIRECTORY = 'import-files';
    protected const IMPORT_FILE_NAME = 'test-import-file.csv';

    /**
     * @var \PyzTest\Zed\ProductDataImport\ProductDataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->getVirtualDirectory([
            static::VIRTUAL_IMPORT_FILE_DIRECTORY => [],
        ]);
        $this->setupFlyAdapterConfig();
        $this->tester->setDependency(
            ProductDataImportDependencyProvider::CLIENT_STORAGE,
            function () {
                return $this->mockStorageClient();
            }
        );

        $this->setupContainerDependencies();
    }

    /**
     * @return void
     */
    protected function setupContainerDependencies(): void
    {
    }

    /**
     * @param string $fileName
     *
     * @return int
     */
    protected function executeProductDataImport(string $fileName): int
    {
        $this->createProductDataImportEntity($fileName);

        $command = new ProductDataImportConsole();
        $arguments = [
            'command' => $command->getName(),
        ];

        return $this->executeCommand($command, $arguments);
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     * @param array $arguments
     *
     * @return int
     */
    protected function executeCommand(Command $command, array $arguments): int
    {
        $commandTester = $this->tester->getCommandTester($command);
        $commandTester->execute($arguments);

        return $commandTester->getStatusCode();
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function createProductDataImportEntity(string $fileName): void
    {
        $entity = new SpyProductDataImport();
        $entity->setStatus(ProductDataImportInterface::STATUS_NEW);
        $entity->setFilePath($fileName);
        $entity->save();
    }

    /**
     * @return void
     */
    protected function setupFlyAdapterConfig(): void
    {
        /**
         * @var \Pyz\Zed\ProductDataImport\ProductDataImportConfig $config
         */
        $config = $this->tester->getModuleConfig();
        $this->tester->setConfig(
            FileSystemConstants::FILESYSTEM_SERVICE,
            array_merge(
                $this->getFileSystemConfiguration(),
                $this->getFilesImportFileSystemConfiguration($config),
                $this->getProductImportFileSystemConfiguration($config),
            )
        );
    }

    /**
     * @param array $attributes
     * @param array $expectedAttributes
     *
     * @return void
     */
    protected function assertProductAttributeValues(array $attributes, array $expectedAttributes): void
    {
        foreach ($expectedAttributes as $expectedKey => $expectedValue) {
            self::assertArrayHasKey($expectedKey, $attributes);
            self::assertEquals($expectedValue, $attributes[$expectedKey]);
        }
    }

    /**
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    protected function findProductAbstractBySku(string $abstractSku): ?ProductAbstractTransfer
    {
        $idProductAbstract = $this->getProductFacade()->findProductAbstractIdBySku($abstractSku);
        if ($idProductAbstract === null) {
            return null;
        }

        return $this->getProductFacade()->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        try {
            return $this->getProductFacade()->getProductConcrete($sku);
        } catch (MissingProductException $exception) {
            return null;
        }
    }

    /**
     * @return \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getProductFacade(): ProductFacadeInterface
    {
        return $this->tester->getLocator()->product()->facade();
    }

    /**
     * @return \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @param \Pyz\Zed\ProductDataImport\ProductDataImportConfig $config
     *
     * @return array
     */
    private function getFilesImportFileSystemConfiguration(ProductDataImportConfig $config): array
    {
        $storageName = $config->getStorageName();
        $filesImportConfiguration = $this->getFileSystemConfiguration()[$storageName];

        return [
            $storageName => array_merge(
                $filesImportConfiguration,
                [
                    'root' => self::BASE_TEST_DATA_DIR,
                    'path' => '/',
                ]
            ),
        ];
    }

    /**
     * @param \Pyz\Zed\ProductDataImport\ProductDataImportConfig $config
     *
     * @return array
     */
    private function getProductImportFileSystemConfiguration(ProductDataImportConfig $config): array
    {
        $storageName = $config::IMPORT_FILE_SYSTEM_NAME;
        $filesImportConfiguration = $this->getFileSystemConfiguration()[$storageName];

        return [
            $storageName => array_merge(
                $filesImportConfiguration,
                [
                    'root' => $this->tester->getVirtualDirectoryUrl('root' . DIRECTORY_SEPARATOR . static::VIRTUAL_IMPORT_FILE_DIRECTORY),
                    'path' => '/',
                ]
            ),
        ];
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockStorageClient(): StorageClientInterface
    {
        return $this->createMock(StorageClientInterface::class);
    }

    /**
     * @return array
     */
    private function getFileSystemConfiguration(): array
    {
        return Config::get(FileSystemConstants::FILESYSTEM_SERVICE);
    }
}
