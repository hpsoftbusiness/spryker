<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Business;

use Pyz\Zed\Category\Business\Model\CategoryReader;
use Spryker\Zed\Category\Business\CategoryBusinessFactory as SprykerCategoryBusinessFactory;
use Spryker\Zed\Category\Business\Model\Category\Category as CategoryEntityModel;
use Spryker\Zed\Category\Business\Model\CategoryReaderInterface;

class CategoryBusinessFactory extends SprykerCategoryBusinessFactory
{
    /**
     * @return \Pyz\Zed\Category\Business\Model\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader(
            $this->getRepository(),
            $this->createPluginExecutor(),
            $this->createCategoryTreeReader()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Business\Model\Category\CategoryInterface
     */
    public function createCategoryCategory()
    {
        return new CategoryEntityModel(
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->createCategoryHydrator()
        );
    }
}
