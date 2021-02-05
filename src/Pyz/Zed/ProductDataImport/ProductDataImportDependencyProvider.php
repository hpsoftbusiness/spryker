<?php

namespace Pyz\Zed\ProductDataImport;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductDataImportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FILE_SYSTEM_SERVICE = 'FILE_SYSTEM_SERVICE';
    public const DATA_IMPORT_FACADE = 'DATA_IMPORT_FACADE';

    /**
     * @param Container $container
     *
     * @return Container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addFileSystemService($container);
        $container = $this->addDataImportFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     * @return Container
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addFileSystemService(Container $container): Container
    {
        $container->set(static::FILE_SYSTEM_SERVICE, $container->getLocator()->fileSystem()->service());

        return $container;
    }

    /**
     * @param Container $container
     * @return Container
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addDataImportFacade(Container $container): Container
    {
        $container->set(static::DATA_IMPORT_FACADE, $container->getLocator()->dataImport()->facade());

        return $container;
    }
}
