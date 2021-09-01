<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStoragePersistenceFactory getFactory()
 */
class ProductAttributeStorageRepository extends AbstractRepository implements ProductAttributeStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $productManagementAttributeVisibilityStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\PyzProductManagementAttributeVisibilityStorageEntityTransfer[]
     */
    public function findFilteredProductManagementAttributeVisibilityStorageEntities(
        FilterTransfer $filterTransfer,
        array $productManagementAttributeVisibilityStorageEntityIds = []
    ): array {
        $query = $this->getFactory()
            ->createProductManagementAttributeVisibilityStorageQuery();

        if ($productManagementAttributeVisibilityStorageEntityIds) {
            $query->filterByIdProductManagementAttributeVisibilityStorage_In(
                $productManagementAttributeVisibilityStorageEntityIds
            );
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
