<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapperInterface as SprykerCategoryMapperInterface;

interface CategoryMapperInterface extends SprykerCategoryMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryEntities
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollectionWithRelations(ObjectCollection $categoryEntities): CategoryCollectionTransfer;
}
