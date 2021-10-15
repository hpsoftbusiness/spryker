<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Zed\Product\Persistence\Mapper\ProductMapper as SprykerProductMapper;

class ProductMapper extends SprykerProductMapper
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productConcreteEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer = parent::mapProductConcreteEntityToTransfer(
            $productConcreteEntity,
            $productConcreteTransfer
        );
        $productConcreteTransfer->setIsAffiliate(
            $productConcreteEntity->getSpyProductAbstract()->getIsAffiliate()
        );

        return $productConcreteTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductEntityToProductConcreteTransferWithoutStores(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer->fromArray($productEntity->toArray(), true);

        $attributes = $this->utilEncodingService->decodeJson($productEntity->getAttributes(), true);
        $productConcreteTransfer->setAttributes($attributes);

        $productConcreteTransfer->setIdProductConcrete($productEntity->getIdProduct());

        $productAbstractEntityTransfer = $productEntity->getSpyProductAbstract();
        if ($productAbstractEntityTransfer !== null) {
            $productConcreteTransfer->setIsAffiliate($productAbstractEntityTransfer->getIsAffiliate());
            $productConcreteTransfer->setAbstractSku($productAbstractEntityTransfer->getSku());
        }

        foreach ($productEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            $productConcreteTransfer->addLocalizedAttributes(
                $this->mapProductLocalizedAttributesEntityToTransfer(
                    $productLocalizedAttributesEntity,
                    new LocalizedAttributesTransfer()
                )
            );
        }

        return $productConcreteTransfer;
    }
}
