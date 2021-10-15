<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapper as SprykerCategoryMapper;

class CategoryMapper extends SprykerCategoryMapper implements CategoryMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryEntities
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollectionWithRelations(ObjectCollection $categoryEntities): CategoryCollectionTransfer
    {
        $categoryCollectionTransfer = new CategoryCollectionTransfer();
        foreach ($categoryEntities as $categoryEntity) {
            $categoryCollectionTransfer->addCategory(
                $this->mapCategoryWithRelations($categoryEntity, new CategoryTransfer())
            );
        }

        return $categoryCollectionTransfer;
    }
}
