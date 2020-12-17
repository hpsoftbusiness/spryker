<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper as SprykerProductMapper;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class ProductMapper extends SprykerProductMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProduct[] $productEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapProductEntityCollectionToRawProductConcreteTransfers(ObjectCollection $productEntities): array
    {
        $productConcreteTransfers = [];

        foreach ($productEntities as $productEntity) {
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->fromArray($productEntity->toArray(), true)
                ->setIdProductConcrete($productEntity->getIdProduct());

            foreach ($productEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
                $productConcreteTransfer->addLocalizedAttributes(
                    $this->mapProductLocalizedAttributesEntityToTransfer($productLocalizedAttributesEntity, new LocalizedAttributesTransfer())
                );
            }

            $productConcreteTransfers[] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function mapProductLocalizedAttributesEntityToTransfer(
        SpyProductLocalizedAttributes $productLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer {
        $localizedAttributesTransfer->fromArray(
            $productLocalizedAttributesEntity->toArray(),
            true
        );

        $localizedAttributesTransfer->setLocale(
            $this->mapLocaleEntityToTransfer($productLocalizedAttributesEntity->getLocale(), new LocaleTransfer())
        );

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        return $localeTransfer->fromArray(
            $localeEntity->toArray(),
            true
        );
    }
}
