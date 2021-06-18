<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Persistence;

use Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributePersistenceFactory getFactory()
 */
class ProductAbstractAttributeRepository extends AbstractRepository implements ProductAbstractAttributeRepositoryInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function findProductAbstractEntitiesByIds(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstracts */
        $productAbstracts = $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find();

        return $productAbstracts;
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer
     */
    public function findProductAbstractAttributeByFkProductAbstract(int $productAbstractId): PyzProductAbstractAttributeEntityTransfer
    {
        $entity = $this->getFactory()
            ->createProductAbstractAttributeQuery()
            ->filterByFkProductAbstract($productAbstractId)
            ->findOneOrCreate();

        return $this->getFactory()
            ->createProductAbstractAttributeMapper()
            ->mapEntityToEntityTransfer($entity, new PyzProductAbstractAttributeEntityTransfer());
    }
}
