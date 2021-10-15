<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Persistence;

use Pyz\Zed\Category\Persistence\Propel\Mapper\CategoryMapper;
use Spryker\Zed\Category\Persistence\CategoryPersistenceFactory as SprykerCategoryPersistenceFactory;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapperInterface;

class CategoryPersistenceFactory extends SprykerCategoryPersistenceFactory
{
    /**
     * @return \Pyz\Zed\Category\Persistence\Propel\Mapper\CategoryMapperInterface
     */
    public function createCategoryMapper(): CategoryMapperInterface
    {
        return new CategoryMapper();
    }
}
