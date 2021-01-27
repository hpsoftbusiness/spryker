<?php

namespace Pyz\Zed\ProductDataImport;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductDataImportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FILE_SYSTEM_SERVICE = 'FILE_SYSTEM_SERVICE';

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
}
